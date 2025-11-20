# Changelog

## Version 1.3.1 - 2024-11-19

### Added
- **Embedded Google Maps**: Route map now displays directly on the page
- Map shows at the top with full route visualization
- Turn-by-turn directions list displayed below the map
- "Open in New Tab" button to view in full Google Maps interface
- Enhanced stop formatting with visual indicators (📍 for start, 🚩 for stops)
- Color-coded stop cards (green for start, blue for stops)

### Improved
- Better visual hierarchy: Map → Directions → Statistics
- Larger map display (600px height)
- More detailed stop information
- Distance from previous stop clearly indicated
- Removed redundant "Open in Google Maps" button from header

## Version 1.3.0 - 2024-11-19

### Changed
- **Google Maps Integration**: Route optimization now uses Google Maps instead of OpenRouteService
- Routes now start from fixed location: 15531 Gladeridge Dr, Houston, TX 77068
- "Open in Google Maps" button provides direct navigation with turn-by-turn directions
- Optimized route includes starting point as first stop
- Google Maps URL format supports up to 9 waypoints + origin + destination

### Technical Changes
- Updated route optimization algorithm to start from specified address
- Modified `buildGoogleMapsUrl()` function to use Google Maps Directions API URL scheme
- Starting address is now geocoded first before client addresses
- Route display now shows "Starting Point" as first item in list

## Version 1.2.0 - 2024-11-19

### Added
- **Active Order Status Column**: Client tables now show if a client has an active flag order
- Visual badges indicating "Active" or "No Active Order" status
- Order status shown in both assigned and unassigned client tables
- Green badge for active orders, gray badge for no active orders

### Improved
- Updated `getClientFlagAddress()` to use specific service address fields
- Now combines `service_address`, `service_city`, and `service_zip_code` fields
- Better address formatting for display
- More accurate address retrieval from flag orders

### Technical Changes
- Added `active_order_status` subquery to client retrieval queries
- Checks for orders with status 'active' or 'pending_setup'
- Added `has_active_order` boolean flag to client data
- Enhanced SQL queries with order status checks

## Version 1.1.1 - 2024-11-19

### Fixed
- Fixed "Call to undefined method Box_Database::lastInsertId()" error when creating routes
- Reverted all database operations to use raw SQL instead of RedBeanPHP ORM
- Uses `SELECT LAST_INSERT_ID()` to get newly created route IDs
- More reliable and compatible with FOSSBilling's database layer

### Technical Changes
- createRoute() now uses raw SQL INSERT with LAST_INSERT_ID()
- updateRoute(), deleteRoute() use raw SQL UPDATE/DELETE
- assignClientToRoute(), removeClientFromRoute() use raw SQL INSERT/DELETE
- All methods tested and confirmed working

## Version 1.1.0 - 2024-11-19

### Added
- **Route Optimization**: New "View Optimized Route" button on route detail pages
- Automatic address geocoding using OpenStreetMap Nominatim API
- Nearest-neighbor route optimization algorithm
- Distance calculations between stops
- Estimated travel time calculation
- Direct integration with OpenRouteService for turn-by-turn directions
- Visual display of optimized route order with distances

### Technical Details
- Uses free OpenStreetMap Nominatim API for geocoding (no API key required)
- Implements Haversine formula for accurate distance calculations
- Respects rate limits with 1-second delays between geocoding requests
- Generates OpenRouteService deep links with optimized waypoints

## Version 1.0.2 - 2024-11-19

### Fixed
- Fixed "Call to undefined method Box_Database::lastInsertId()" error
- Converted all database operations to use RedBeanPHP ORM (FOSSBilling's standard)
- Updated createRoute, updateRoute, deleteRoute, assignClientToRoute, and removeClientFromRoute methods
- Now using `dispense()`, `store()`, `load()`, `trash()` and `find()` methods

## Version 1.0.1 - 2024-11-19

### Fixed
- Fixed "Call to undefined method Box_AppAdmin::getRequestParam()" error
- Changed to use `$this->di['request']->request->get()` for POST parameters
- Updated all controller methods to use correct FOSSBilling request handling

## Version 1.0.0 - 2024-11-19

### Added
- Initial release
- Routes management interface
- Client assignment functionality
- Route listing with client counts
- Unassigned clients tracking
- Search functionality for clients
- Navigation integration
- Multiple template naming support

### Fixed
- 404 routing issues with multiple URL patterns
- Template naming conventions
- Navigation menu registration
