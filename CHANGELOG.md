# Changelog

## Version 1.4.2 - 2024-11-21

### Added
- **Admin Navigation Menu**: Routes extension now appears in the admin sidebar under System section
  - "All Routes" submenu for accessing route management
  - "Settings" submenu for extension configuration
- **Comprehensive Settings Page**: New settings interface with three main sections:
  - Google Maps API Configuration (API key and starting address)
  - Order Address Field Mapping (configurable field names for address data)
  - Order Details Field Mapping (configurable field names for order-specific data)
- **Database Settings Storage**: New `routes_settings` table for persistent configuration
  - Stores all extension settings with key-value pairs
  - Automatically populated with sensible defaults on installation
  - 10 configurable settings available
- **Flexible Field Mapping**: Extension now uses configurable field names from settings
  - No code changes required for custom database structures
  - Works with any FOSSBilling installation
  - Field names include: address, city, state, ZIP, instructions, flag quantity, flag type, flag size
- **Enhanced Turn-by-Turn Directions**: Comprehensive client information now displays at each waypoint
  - Client name, email, and address at each stop
  - Complete flag information with quantities and types
  - Add-on items with quantities
  - Service instructions highlighted in orange
  - Distance and duration to each stop
  - Beautiful gradient-styled info panels

### Changed
- Updated navigation.php to include Settings submenu
- Enhanced Service.php with settings management methods:
  - `getSetting($key)` - Get individual setting
  - `getAllSettings()` - Get all settings as array
  - `updateSetting($key, $value)` - Update single setting
  - `updateSettings($settings)` - Update multiple settings
- Modified `getOrderDetails()` to use configurable field names from settings
- Updated Controller/Admin.php with settings routes and handlers:
  - `get_settings()` - Display settings page
  - `post_settings_save()` - Save settings changes
- Enhanced Api/Admin.php with settings API methods:
  - `get_setting($data)` - API to get single setting
  - `get_all_settings($data)` - API to get all settings
  - `update_setting($data)` - API to update single setting
  - `update_settings($data)` - API to update multiple settings
- Updated install() method to create settings table and populate defaults
- Updated uninstall() method to drop settings table
- **Completely rewrote `enhanceDirectionsPanel()` function** in navigation template:
  - Now properly displays client information for each waypoint
  - Includes all order details, flags, addons, and instructions
  - Better styling with gradient backgrounds and icons
  - Shows distance and duration for each leg
  - Matches the information shown in map marker popups
  - More reliable insertion into Google Maps directions panel
- **Updated routes index table** with icon-based action buttons:
  - Changed from text buttons to icon-only buttons (btn-icon)
  - Uses SVG icons for View, Navigate, Edit, and Delete actions
  - Inline SVG for map/navigation icon
  - Cleaner, more professional appearance
  - Better visual hierarchy

### Technical Changes
- Created mod_routes_settings.html.twig template for settings page
- Settings page includes success/error message handling
- Form validation and user-friendly help text for each setting
- Added configuration notes section with best practices
- All settings default to standard FOSSBilling field names
- Version bumped from 1.4.1 to 1.4.2 in manifest.json
- Improved directions panel enhancement with better DOM manipulation
- Added fallback retry mechanism for directions panel rendering

### Database Changes
- New table: `routes_settings` with columns:
  - id (primary key)
  - setting_key (unique)
  - setting_value (text)
  - updated_at (datetime)
- Default settings:
  - google_maps_api_key: (empty string)
  - starting_address: "15531 Gladeridge Dr, Houston, TX 77068"
  - order_address_field: "service_address"
  - order_city_field: "service_city"
  - order_zip_field: "service_zip_code"
  - order_state_field: "service_state"
  - order_instructions_field: "service_instructions"
  - order_flag_quantity_field: "us_flag_quantity"
  - order_flag_type_field: "flag_type"
  - order_flag_size_field: "flag_size"

### Benefits
- Easy configuration without code editing
- Flexible database compatibility
- Secure API key storage
- Professional admin interface
- Better organization with clear navigation
- Field mapping adapts to any database structure

## Version 1.4.1 - 2024-11-20

### Added
- **Order Information Display**: Route pages now show complete order details for each client
  - Order title and product information
  - Addon items with quantities
  - Service instructions prominently displayed
  - Flag type and size information
- **Flag Summary Panel**: New visual panel on both route and navigation pages showing:
  - Total count of US Flags across all route stops
  - Breakdown of flag types by location
  - Flag sizes by location
  - Beautiful gradient design for quick reference
- **Enhanced Map Markers**: Navigation page markers now include:
  - Client name and contact information
  - Complete order details
  - Flag information (type, size, quantity)
  - All addon items
  - Service instructions highlighted in orange
- **Enriched Directions Panel**: Turn-by-turn directions now display:
  - Client information at each stop
  - Flag details for each location
  - Service instructions for easy reference while driving
- **Improved Client Table**: Route details page includes new columns:
  - Order Info (with addons)
  - Flags (types, sizes, quantities)
  - Service Instructions (scrollable for long text)

### Changed
- Enhanced `getRouteClients()` to fetch comprehensive order information
- Updated route details table to display 7 columns instead of 6
- Modified navigation JavaScript to handle and display rich client data
- Improved info window design with color-coded sections

### Technical Changes
- Added `getOrderDetails()` method to Service.php
- Added `getRouteFlagSummary()` method to calculate flag totals
- Enhanced database queries to include order and addon data
- Updated controllers to pass flag summary to templates
- Implemented custom map markers with detailed info windows
- Added direction panel enhancement function

## Version 1.4.0 - 2024-11-20

### Added
- **Dedicated Route Navigation Page**: New standalone page for route navigation with turn-by-turn directions
- **Full Google Maps APIs Integration**: Complete integration with Google Maps Platform
  - Maps JavaScript API for interactive map display
  - Directions API for route optimization and navigation
  - Automatic waypoint optimization using Google's algorithms
- **Navigation Buttons**: Added "Navigate" button to both route listing table and route details page
- **Mobile App Integration**: Direct links to open routes in Google Maps mobile app
- **Route Summary Dashboard**: Displays total distance, estimated duration, and number of stops
- **Interactive Map Display**: Full-featured map with pan, zoom, and real-time route visualization
- **Comprehensive Documentation**: New GOOGLE_MAPS_SETUP.md guide with setup instructions

### Changed
- **Removed OpenRouteService**: Completely removed all OpenRouteService code and dependencies
- **Removed Client-Side Optimization**: Replaced manual nearest-neighbor algorithm with Google's professional routing
- **Updated Route Display**: Moved route visualization from inline to dedicated navigation page
- **Simplified Route Details Page**: Focused on client management without embedded map
- **Enhanced User Experience**: Cleaner separation between route management and navigation

### Improved
- **Route Optimization**: Using Google's advanced algorithms instead of simple nearest-neighbor
- **Address Handling**: Better geocoding and address validation with Google's APIs
- **Performance**: Faster route calculations and smoother map rendering
- **Reliability**: Industry-standard APIs with 99.9% uptime SLA
- **Mobile Experience**: Better navigation support for mobile devices

### Technical Changes
- Added `get_navigation()` controller method for navigation page
- Created new `mod_routes_navigation.html.twig` template
- Updated `mod_routes_route.html.twig` with navigation button
- Updated `mod_routes_index.html.twig` with navigate buttons in table
- Removed manual geocoding and optimization JavaScript code
- Integrated Google Maps DirectionsService and DirectionsRenderer

### Documentation
- Added GOOGLE_MAPS_SETUP.md with comprehensive API setup guide
- Included pricing information and free tier details
- Added troubleshooting section for common issues
- Updated installation and configuration instructions

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
