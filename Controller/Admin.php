<?php
/**
 * Routes extension - Admin Controller
 * 
 * @copyright Xavier
 * @license   Apache-2.0
 */

namespace Box\Mod\Routes\Controller;

class Admin implements \FOSSBilling\InjectionAwareInterface
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
     * Register admin routes and menu
     */
    public function register(\Box_App &$app)
    {
        $app->get('/routes', 'get_index', [], static::class);
        $app->get('/routes/', 'get_index', [], static::class);
        $app->get('/routes/index', 'get_index', [], static::class);
        $app->get('/routes/route/:id', 'get_route', ['id' => '[0-9]+'], static::class);
        $app->get('/routes/navigation/:id', 'get_navigation', ['id' => '[0-9]+'], static::class);
        $app->post('/routes/create', 'post_create', [], static::class);
        $app->post('/routes/update', 'post_update', [], static::class);
        $app->post('/routes/delete', 'post_delete', [], static::class);
        $app->post('/routes/assign', 'post_assign', [], static::class);
        $app->post('/routes/remove', 'post_remove', [], static::class);
    }

    /**
     * Main routes index page
     */
    public function get_index(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        
        $api = $this->di['api_admin'];
        $routes = $api->routes_get_list([]);
        
        return $app->render('mod_routes_index', [
            'routes' => $routes
        ]);
    }

    /**
     * View a specific route with assigned clients
     */
    public function get_route(\Box_App $app, $id)
    {
        $this->di['is_admin_logged'];
        
        $api = $this->di['api_admin'];
        
        try {
            $route = $api->routes_get(['id' => $id]);
            $assigned_clients = $api->routes_get_route_clients(['route_id' => $id]);
            $unassigned_clients = $api->routes_get_unassigned_clients([]);
            $all_routes = $api->routes_get_list([]);
            
            // Get flag summary
            $service = $this->di['mod_service']('routes');
            $flag_summary = $service->getRouteFlagSummary($assigned_clients);
            
            return $app->render('mod_routes_route', [
                'route' => $route,
                'assigned_clients' => $assigned_clients,
                'unassigned_clients' => $unassigned_clients,
                'all_routes' => $all_routes,
                'flag_summary' => $flag_summary
            ]);
        } catch (\Exception $e) {
            $this->di['logger']->error('Error loading route: ' . $e->getMessage());
            throw new \Exception('Route not found');
        }
    }

    /**
     * View route navigation page with turn-by-turn directions
     */
    public function get_navigation(\Box_App $app, $id)
    {
        $this->di['is_admin_logged'];
        
        $api = $this->di['api_admin'];
        
        try {
            $route = $api->routes_get(['id' => $id]);
            $assigned_clients = $api->routes_get_route_clients(['route_id' => $id]);
            
            // Get flag summary
            $service = $this->di['mod_service']('routes');
            $flag_summary = $service->getRouteFlagSummary($assigned_clients);
            
            return $app->render('mod_routes_navigation', [
                'route' => $route,
                'assigned_clients' => $assigned_clients,
                'flag_summary' => $flag_summary
            ]);
        } catch (\Exception $e) {
            $this->di['logger']->error('Error loading route navigation: ' . $e->getMessage());
            throw new \Exception('Route not found');
        }
    }

    /**
     * Create a new route
     */
    public function post_create(\Box_App $app)
    {
        $api = $this->di['api_admin'];
        $request = $this->di['request'];
        
        try {
            $name = $request->request->get('name');
            $description = $request->request->get('description', '');
            
            $result = $api->routes_create([
                'name' => $name,
                'description' => $description
            ]);
            
            $this->di['logger']->info('Created route: ' . $name);
            
            return $app->redirect('/routes');
        } catch (\Exception $e) {
            $this->di['logger']->error('Error creating route: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a route
     */
    public function post_update(\Box_App $app)
    {
        $api = $this->di['api_admin'];
        $request = $this->di['request'];
        
        try {
            $id = $request->request->get('id');
            $name = $request->request->get('name');
            $description = $request->request->get('description', '');
            
            $api->routes_update([
                'id' => $id,
                'name' => $name,
                'description' => $description
            ]);
            
            $this->di['logger']->info('Updated route ID: ' . $id);
            
            return $app->redirect('/routes');
        } catch (\Exception $e) {
            $this->di['logger']->error('Error updating route: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a route
     */
    public function post_delete(\Box_App $app)
    {
        $api = $this->di['api_admin'];
        $request = $this->di['request'];
        
        try {
            $id = $request->request->get('id');
            
            $api->routes_delete([
                'id' => $id
            ]);
            
            $this->di['logger']->info('Deleted route ID: ' . $id);
            
            return $app->redirect('/routes');
        } catch (\Exception $e) {
            $this->di['logger']->error('Error deleting route: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Assign a client to a route
     */
    public function post_assign(\Box_App $app)
    {
        $api = $this->di['api_admin'];
        $request = $this->di['request'];
        
        try {
            $route_id = $request->request->get('route_id');
            $client_id = $request->request->get('client_id');
            
            $api->routes_assign_client([
                'client_id' => $client_id,
                'route_id' => $route_id
            ]);
            
            $this->di['logger']->info('Assigned client to route');
            
            return $app->redirect('/routes/route/' . $route_id);
        } catch (\Exception $e) {
            $this->di['logger']->error('Error assigning client: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove a client from a route
     */
    public function post_remove(\Box_App $app)
    {
        $api = $this->di['api_admin'];
        $request = $this->di['request'];
        
        try {
            $route_id = $request->request->get('route_id');
            $client_id = $request->request->get('client_id');
            
            $api->routes_remove_client([
                'client_id' => $client_id,
                'route_id' => $route_id
            ]);
            
            $this->di['logger']->info('Removed client from route');
            
            return $app->redirect('/routes/route/' . $route_id);
        } catch (\Exception $e) {
            $this->di['logger']->error('Error removing client: ' . $e->getMessage());
            throw $e;
        }
    }
}
