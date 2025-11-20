<?php
/**
 * Routes extension for FOSSBilling
 * 
 * @copyright Xavier
 * @license   Apache-2.0
 */

namespace Box\Mod\Routes;

class Service implements \FOSSBilling\InjectionAwareInterface
{
    protected ?\Pimple\Container $di = null;

    public function setDi(\Pimple\Container $di): void
    {
        $this->di = $di;
    }

    public function getDi(): ?\Pimple\Container
    {
        return $this->di;
    }

    /**
     * Install the extension - create necessary database tables
     */
    public function install()
    {
        $db = $this->di['db'];
        
        // Create routes table
        $sql = "
        CREATE TABLE IF NOT EXISTS `routes` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $db->exec($sql);
        
        // Create client_routes table for assignments
        $sql = "
        CREATE TABLE IF NOT EXISTS `client_routes` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `route_id` INT(11) NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            KEY `client_id` (`client_id`),
            KEY `route_id` (`route_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
        $db->exec($sql);

        return true;
    }

    /**
     * Uninstall the extension - remove database tables
     */
    public function uninstall()
    {
        $db = $this->di['db'];
        
        $db->exec("DROP TABLE IF EXISTS `client_routes`");
        $db->exec("DROP TABLE IF EXISTS `routes`");

        return true;
    }

    /**
     * Get all routes with client count
     * 
     * @return array
     */
    public function getRoutesList()
    {
        $db = $this->di['db'];
        
        $sql = "
            SELECT 
                r.id,
                r.name,
                r.description,
                COUNT(DISTINCT cr.client_id) as client_count
            FROM routes r
            LEFT JOIN client_routes cr ON r.id = cr.route_id
            GROUP BY r.id
            ORDER BY r.name ASC
        ";
        
        return $db->getAll($sql);
    }

    /**
     * Get a single route by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getRoute($id)
    {
        $db = $this->di['db'];
        
        $sql = "SELECT * FROM routes WHERE id = :id";
        return $db->getRow($sql, ['id' => $id]);
    }

    /**
     * Create a new route
     * 
     * @param string $name
     * @param string $description
     * @return int Route ID
     */
    public function createRoute($name, $description = '')
    {
        $db = $this->di['db'];
        
        $sql = "
            INSERT INTO routes (name, description, created_at, updated_at)
            VALUES (:name, :description, NOW(), NOW())
        ";
        
        $db->exec($sql, [
            'name' => $name,
            'description' => $description
        ]);
        
        // Get the last inserted ID using a SELECT query
        $result = $db->getRow("SELECT LAST_INSERT_ID() as id");
        
        if (!$result || !isset($result['id'])) {
            throw new \Exception('Failed to create route - no ID returned');
        }
        
        return (int)$result['id'];
    }

    /**
     * Update a route
     * 
     * @param int $id
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function updateRoute($id, $name, $description = '')
    {
        $db = $this->di['db'];
        
        $sql = "
            UPDATE routes 
            SET name = :name, description = :description, updated_at = NOW()
            WHERE id = :id
        ";
        
        $db->exec($sql, [
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
        
        return true;
    }

    /**
     * Delete a route
     * 
     * @param int $id
     * @return bool
     */
    public function deleteRoute($id)
    {
        $db = $this->di['db'];
        
        // First, remove all client assignments
        $db->exec("DELETE FROM client_routes WHERE route_id = :id", ['id' => $id]);
        
        // Then delete the route
        $db->exec("DELETE FROM routes WHERE id = :id", ['id' => $id]);
        
        return true;
    }

    /**
     * Get clients assigned to a route with their details
     * 
     * @param int $route_id
     * @return array
     */
    public function getRouteClients($route_id)
    {
        $db = $this->di['db'];
        
        $sql = "
            SELECT 
                c.id as client_id,
                c.first_name,
                c.last_name,
                c.email,
                cg.title as client_group_name,
                cr.created_at as assigned_at,
                (
                    SELECT co.title 
                    FROM client_order co 
                    WHERE co.client_id = c.id 
                    AND co.product_id IN (
                        SELECT p.id FROM product p WHERE p.title LIKE '%flag%'
                    )
                    ORDER BY co.created_at DESC 
                    LIMIT 1
                ) as flag_order_title,
                (
                    SELECT co.status
                    FROM client_order co
                    WHERE co.client_id = c.id
                    AND co.product_id IN (
                        SELECT p.id FROM product p WHERE p.title LIKE '%flag%'
                    )
                    AND co.status IN ('active', 'pending_setup')
                    ORDER BY co.created_at DESC
                    LIMIT 1
                ) as active_order_status
            FROM client c
            INNER JOIN client_routes cr ON c.id = cr.client_id
            LEFT JOIN client_group cg ON c.client_group_id = cg.id
            WHERE cr.route_id = :route_id
            ORDER BY c.last_name, c.first_name
        ";
        
        $clients = $db->getAll($sql, ['route_id' => $route_id]);
        
        // Get address for each client from their flag order
        foreach ($clients as &$client) {
            $client['address'] = $this->getClientFlagAddress($client['client_id']);
            $client['has_active_order'] = !empty($client['active_order_status']);
        }
        
        return $clients;
    }

    /**
     * Get clients not assigned to any route
     * 
     * @return array
     */
    public function getUnassignedClients()
    {
        $db = $this->di['db'];
        
        $sql = "
            SELECT 
                c.id as client_id,
                c.first_name,
                c.last_name,
                c.email,
                cg.title as client_group_name,
                (
                    SELECT co.status
                    FROM client_order co
                    WHERE co.client_id = c.id
                    AND co.product_id IN (
                        SELECT p.id FROM product p WHERE p.title LIKE '%flag%'
                    )
                    AND co.status IN ('active', 'pending_setup')
                    ORDER BY co.created_at DESC
                    LIMIT 1
                ) as active_order_status
            FROM client c
            LEFT JOIN client_group cg ON c.client_group_id = cg.id
            WHERE c.id NOT IN (SELECT client_id FROM client_routes)
            ORDER BY c.last_name, c.first_name
        ";
        
        $clients = $db->getAll($sql);
        
        // Get address for each client from their flag order
        foreach ($clients as &$client) {
            $client['address'] = $this->getClientFlagAddress($client['client_id']);
            $client['has_active_order'] = !empty($client['active_order_status']);
        }
        
        return $clients;
    }

    /**
     * Get client's address from their flag order
     * 
     * @param int $client_id
     * @return string
     */
    private function getClientFlagAddress($client_id)
    {
        $db = $this->di['db'];
        
        // Try to get address from order meta/config
        $sql = "
            SELECT co.config
            FROM client_order co
            INNER JOIN product p ON co.product_id = p.id
            WHERE co.client_id = :client_id
            AND (p.title LIKE '%flag%' OR p.title LIKE '%Flag%')
            ORDER BY co.created_at DESC
            LIMIT 1
        ";
        
        $order = $db->getRow($sql, ['client_id' => $client_id]);
        
        if ($order && $order['config']) {
            $config = json_decode($order['config'], true);
            
            // Check for service address fields
            if (isset($config['service_address'])) {
                $comb = array_filter([
                    $config['service_address'],
                    isset($config['service_city']) ? $config['service_city'] : null,
                    isset($config['service_zip_code']) ? $config['service_zip_code'] : null
                ]);
                return implode(', ', $comb);
            } elseif (isset($config['delivery_address'])) {
                return $config['delivery_address'];
            } elseif (isset($config['address'])) {
                return $config['address'];
            }
        }
        
        // Fallback to client's address
        $sql = "SELECT address_1, address_2, city, state, postcode FROM client WHERE id = :client_id";
        $client = $db->getRow($sql, ['client_id' => $client_id]);
        
        if ($client) {
            $parts = array_filter([
                $client['address_1'],
                $client['address_2'],
                $client['city'],
                $client['state'],
                $client['postcode']
            ]);
            return implode(', ', $parts);
        }
        
        return 'N/A';
    }

    /**
     * Assign a client to a route
     * 
     * @param int $client_id
     * @param int $route_id
     * @return bool
     */
    public function assignClientToRoute($client_id, $route_id)
    {
        $db = $this->di['db'];
        
        // Check if already assigned
        $exists = $db->getRow(
            "SELECT id FROM client_routes WHERE client_id = :client_id AND route_id = :route_id",
            ['client_id' => $client_id, 'route_id' => $route_id]
        );
        
        if ($exists) {
            return true; // Already assigned
        }
        
        // Create new assignment
        $sql = "
            INSERT INTO client_routes (client_id, route_id, created_at)
            VALUES (:client_id, :route_id, NOW())
        ";
        
        $db->exec($sql, [
            'client_id' => $client_id,
            'route_id' => $route_id
        ]);
        
        return true;
    }

    /**
     * Remove a client from a route
     * 
     * @param int $client_id
     * @param int $route_id
     * @return bool
     */
    public function removeClientFromRoute($client_id, $route_id = null)
    {
        $db = $this->di['db'];
        
        if ($route_id) {
            // Remove from specific route
            $sql = "DELETE FROM client_routes WHERE client_id = :client_id AND route_id = :route_id";
            $db->exec($sql, ['client_id' => $client_id, 'route_id' => $route_id]);
        } else {
            // Remove from all routes
            $sql = "DELETE FROM client_routes WHERE client_id = :client_id";
            $db->exec($sql, ['client_id' => $client_id]);
        }
        
        return true;
    }

    /**
     * Get route ID for a client
     * 
     * @param int $client_id
     * @return int|null
     */
    public function getClientRoute($client_id)
    {
        $db = $this->di['db'];
        
        $sql = "SELECT route_id FROM client_routes WHERE client_id = :client_id LIMIT 1";
        $result = $db->getRow($sql, ['client_id' => $client_id]);
        
        return $result ? $result['route_id'] : null;
    }
}
