# Routes Extension for FOSSBilling

A comprehensive extension for managing delivery routes and client assignments in FOSSBilling.

## Features

- **Routes Management**: Create, edit, and delete delivery routes
- **Client Assignment**: Assign clients to specific routes
- **Route Overview**: View all routes with client counts at a glance
- **Route Details**: See detailed information about clients on each route including:
  - Client name with link to profile
  - Email address
  - Service address from flag orders (including city and zip code)
  - Client group membership
  - **Active order status** - Visual indicator showing if client has an active flag order
- **Route Optimization**: View optimized delivery routes with:
  - Automatic address geocoding
  - Nearest-neighbor route optimization algorithm starting from 15531 Gladeridge Dr, Houston, TX 77068
  - Distance calculations between stops
  - Estimated travel time
  - Direct link to Google Maps for turn-by-turn directions
- **Unassigned Clients**: Track and assign clients who aren't on any route yet
- **Quick Assignment**: Assign unassigned clients to any route directly from the route detail page
- **Search Functionality**: Search through unassigned clients to quickly find and assign them

## Installation

1. Download or clone this extension
2. Upload the entire `Routes` folder to your FOSSBilling installation at:
   ```
   /library/Box/Mod/Routes/
   ```

3. Log in to your FOSSBilling admin panel
4. Navigate to **Extensions** → **Overview**
5. Find "Routes" in the list and click **Activate**
6. The extension will automatically create the necessary database tables

## File Structure

```
Routes/
├── manifest.json           # Extension metadata
├── Service.php             # Core business logic
├── Controller/
│   └── Admin.php          # Admin controller
├── Api/
│   └── Admin.php          # Admin API endpoints
└── html_admin/
    ├── mod_routes_index.html.twig    # Routes listing page
    └── mod_routes_route.html.twig    # Route detail page
```

## Database Schema

### `routes` Table
- `id` - Primary key
- `name` - Route name
- `description` - Route description
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### `client_routes` Table
- `id` - Primary key
- `client_id` - Foreign key to client
- `route_id` - Foreign key to route
- `created_at` - Assignment timestamp

## Usage

### Creating a Route

1. Navigate to **Routes** in the admin menu
2. Click **Create New Route**
3. Enter a route name (required) and optional description
4. Click **Create Route**

### Viewing Routes

The main Routes page displays a table with:
- Route name (clickable to view details)
- Description
- Number of assigned clients
- Action buttons (View, Edit, Delete)

### Assigning Clients to Routes

1. Click on a route name to view details
2. Scroll to the **Unassigned Clients** section
3. Click **Assign to Route** for any client you want to add
4. Or use the dropdown to quickly assign to a different route

### Removing Clients from Routes

1. Click on a route name to view details
2. In the **Assigned Clients** table, click **Remove** next to any client
3. Confirm the removal

### Editing a Route

1. On the main Routes page, click **Edit** next to the route
2. Update the name or description
3. Click **Update Route**

### Deleting a Route

1. On the main Routes page, click **Delete** next to the route
2. Confirm deletion
3. All client assignments will be removed (clients will become unassigned)

### Viewing Optimized Route Directions

1. Click on a route name to view details
2. Click the **View Optimized Route** button (only visible if route has clients with addresses)
3. Wait while addresses are geocoded (may take a few seconds)
4. **View the embedded Google Maps** showing the full route visualization at the top
5. **Review turn-by-turn directions** listed below the map with distances
6. Click **Open in New Tab** to view in full Google Maps interface for navigation

**Display Layout:**
- **Route Map** (top): Interactive Google Maps showing complete route
- **Turn-by-Turn Directions** (middle): Ordered list of stops with distances
- **Route Statistics** (bottom): Total distance and estimated duration

**Note**: Route optimization uses:
- Starting point: 15531 Gladeridge Dr, Houston, TX 77068
- OpenStreetMap's Nominatim for geocoding (free, no API key needed)
- Nearest-neighbor algorithm for route optimization starting from your location
- Google Maps for visualization and turn-by-turn directions

## API Endpoints

All API endpoints are available under the `routes` module:

### Admin API

- `routes_get_list` - Get all routes with client counts
- `routes_get` - Get a specific route by ID
- `routes_create` - Create a new route
- `routes_update` - Update an existing route
- `routes_delete` - Delete a route
- `routes_get_route_clients` - Get clients assigned to a route
- `routes_get_unassigned_clients` - Get all unassigned clients
- `routes_assign_client` - Assign a client to a route
- `routes_remove_client` - Remove a client from a route

### Example API Call

```php
// Get all routes
$routes = $api->routes_get_list([]);

// Assign a client to a route
$api->routes_assign_client([
    'client_id' => 123,
    'route_id' => 5
]);
```

## Address Resolution

The extension attempts to get client addresses from:
1. Flag order configuration (prioritizes `service_address`, `service_city`, and `service_zip_code` fields combined)
2. Alternative order fields (`delivery_address` or generic `address`)
3. Falls back to client's registered address if no order address is found

**Address Field Priority:**
- `service_address` + `service_city` + `service_zip_code` (combined with commas)
- `delivery_address`
- `address`
- Client's default address

## Requirements

- FOSSBilling 4.22.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher

## Customization

### Modifying Address Fields

If your flag orders use different field names for addresses, modify the `getClientFlagAddress()` method in `Service.php`:

```php
if (isset($config['your_custom_address_field'])) {
    return $config['your_custom_address_field'];
}
```

### Styling

The templates include inline CSS for basic styling. To customize:
1. Edit the `<style>` sections in the `.html.twig` files
2. Or create a custom theme that overrides the default styles

## Troubleshooting

### Routes menu not appearing
- Ensure the extension is activated
- Clear your browser cache
- Check file permissions (755 for directories, 644 for files)

### Clients not showing addresses
- Verify that flag orders exist for the clients
- Check that order configurations contain address data
- Ensure the address field names match what the extension expects

### Database errors on install
- Verify database user has CREATE TABLE permissions
- Check FOSSBilling error logs at `/data/log/application.log`

## Support

For issues, questions, or contributions, please visit the GitHub repository or contact the developer.

## License

Apache License 2.0

## Credits

Developed by Xavier for FOSSBilling
