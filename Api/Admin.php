<?php
/**
 * Routes extension - Admin API
 * 
 * @copyright Xavier
 * @license   Apache-2.0
 */

namespace Box\Mod\Routes\Api;

class Admin extends \Api_Abstract
{
    /**
     * Get list of all routes with client counts
     * 
     * @return array
     */
    public function get_list($data)
    {
        $service = $this->getService();
        return $service->getRoutesList();
    }

    /**
     * Get a single route details
     * 
     * @param array $data - must contain 'id'
     * @return array
     * @throws \Exception
     */
    public function get($data)
    {
        $required = ['id' => 'Route ID is required'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $route = $service->getRoute($data['id']);
        
        if (!$route) {
            throw new \Exception('Route not found');
        }
        
        return $route;
    }

    /**
     * Create a new route
     * 
     * @param array $data - must contain 'name', optional 'description'
     * @return array
     * @throws \Exception
     */
    public function create($data)
    {
        $required = ['name' => 'Route name is required'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $id = $service->createRoute(
            $data['name'],
            $data['description'] ?? ''
        );
        
        return [
            'id' => $id,
            'message' => 'Route created successfully'
        ];
    }

    /**
     * Update a route
     * 
     * @param array $data - must contain 'id', 'name', optional 'description'
     * @return array
     * @throws \Exception
     */
    public function update($data)
    {
        $required = [
            'id' => 'Route ID is required',
            'name' => 'Route name is required'
        ];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $service->updateRoute(
            $data['id'],
            $data['name'],
            $data['description'] ?? ''
        );
        
        return [
            'success' => true,
            'message' => 'Route updated successfully'
        ];
    }

    /**
     * Delete a route
     * 
     * @param array $data - must contain 'id'
     * @return array
     * @throws \Exception
     */
    public function delete($data)
    {
        $required = ['id' => 'Route ID is required'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $service->deleteRoute($data['id']);
        
        return [
            'success' => true,
            'message' => 'Route deleted successfully'
        ];
    }

    /**
     * Get clients assigned to a route
     * 
     * @param array $data - must contain 'route_id'
     * @return array
     * @throws \Exception
     */
    public function get_route_clients($data)
    {
        $required = ['route_id' => 'Route ID is required'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        return $service->getRouteClients($data['route_id']);
    }

    /**
     * Get clients not assigned to any route
     * 
     * @return array
     */
    public function get_unassigned_clients($data)
    {
        $service = $this->getService();
        return $service->getUnassignedClients();
    }

    /**
     * Assign a client to a route
     * 
     * @param array $data - must contain 'client_id' and 'route_id'
     * @return array
     * @throws \Exception
     */
    public function assign_client($data)
    {
        $required = [
            'client_id' => 'Client ID is required',
            'route_id' => 'Route ID is required'
        ];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $service->assignClientToRoute($data['client_id'], $data['route_id']);
        
        return [
            'success' => true,
            'message' => 'Client assigned to route successfully'
        ];
    }

    /**
     * Remove a client from a route
     * 
     * @param array $data - must contain 'client_id', optional 'route_id'
     * @return array
     * @throws \Exception
     */
    public function remove_client($data)
    {
        $required = ['client_id' => 'Client ID is required'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $service = $this->getService();
        $service->removeClientFromRoute(
            $data['client_id'],
            $data['route_id'] ?? null
        );
        
        return [
            'success' => true,
            'message' => 'Client removed from route successfully'
        ];
    }
}
