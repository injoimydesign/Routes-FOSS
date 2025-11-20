# Routes Extension for FOSSBilling

A comprehensive route management system for FOSSBilling that helps organize and optimize delivery routes for clients. Now powered by **Google Maps Platform** for professional-grade navigation and route optimization.

## ✨ Key Features

- **📍 Route Management**: Create and manage multiple delivery routes
- **👥 Client Assignment**: Assign clients to routes with easy-to-use interface
- **🗺️ Google Maps Integration**: Professional route optimization and navigation
- **📱 Mobile Ready**: Direct integration with Google Maps mobile app
- **🧭 Turn-by-Turn Directions**: Detailed navigation instructions
- **📊 Route Analytics**: Distance, duration, and stop count summaries
- **🔄 Auto-Optimization**: Google automatically determines the most efficient route order
- **🏁 Custom Starting Point**: Configure your depot/starting location

## 🚀 New in Version 1.4.0

### Dedicated Navigation Page
- Standalone route navigation page with full-screen map
- Professional turn-by-turn directions
- Route summary dashboard
- Mobile app integration button

### Google Maps Platform Integration
- **Maps JavaScript API**: Interactive map display with real-time updates
- **Directions API**: Advanced route optimization algorithms
- **Geocoding API**: Accurate address validation and conversion
- Complete removal of OpenRouteService dependency

### Enhanced User Experience
- Navigate button on routes listing table
- View navigation button on route details page
- Cleaner interface focused on route management
- Better mobile navigation support

## 📋 Requirements

- FOSSBilling v0.9.0 or higher
- PHP 8.1 or higher
- MySQL/MariaDB database
- **Google Maps API Key** (see setup guide below)

## 📥 Installation

1. Download the extension files
2. Upload to your FOSSBilling installation: `library/Box/Mod/Routes/`
3. In FOSSBilling admin, go to **Extensions > Modules**
4. Find "Routes" and click **Activate**
5. Set up your Google Maps API key (see configuration below)

## ⚙️ Configuration

### Google Maps API Setup

1. **Create a Google Cloud Project**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project
   - Enable billing (required for API usage)

2. **Enable Required APIs**
   - Maps JavaScript API
   - Directions API  
   - Geocoding API (recommended)

3. **Create API Key**
   - Go to APIs & Services > Credentials
   - Create API Key
   - Restrict the key (recommended for security)

4. **Configure Extension**
   - Open `html_admin/mod_routes_navigation.html.twig`
   - Replace `YOUR_GOOGLE_MAPS_API_KEY` with your actual key
   - Save the file

**📖 For detailed setup instructions, see [GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md)**

### Starting Address

Update your starting location in `mod_routes_navigation.html.twig`:

```javascript
startAddress: "15531 Gladeridge Dr, Houston, TX 77068"
```

## 💰 Pricing

Google Maps APIs use pay-as-you-go pricing with a generous free tier:

- **$200 FREE monthly credit** (covers ~40,000 directions requests)
- Maps JavaScript API: $7 per 1,000 loads
- Directions API: $5 per 1,000 requests
- Geocoding API: $5 per 1,000 requests

For most small to medium businesses, the free tier is sufficient.

## 📖 Usage

### Creating Routes

1. Go to **Routes** in the admin menu
2. Click **Create New Route**
3. Enter route name and description
4. Click "Create Route"

### Assigning Clients

1. Open a route
2. Scroll to "Unassigned Clients"
3. Click **Assign to Route** for each client
4. Or use the dropdown to quick-assign to other routes

### Viewing Navigation

**From Routes List:**
1. Find a route with clients assigned
2. Click the **Navigate** button
3. View optimized route with turn-by-turn directions

**From Route Details:**
1. Open any route with clients
2. Click **View Route Navigation**
3. Access full navigation interface

### Using Mobile Navigation

1. Open the navigation page on your phone
2. Click **Open in Google Maps App**
3. Route opens in Google Maps for real-time GPS navigation

## 🎯 Features in Detail

### Route Optimization
Google's Directions API automatically:
- Calculates the most efficient route order
- Minimizes total travel distance
- Considers real-time traffic data (with proper API setup)
- Provides accurate travel time estimates

### Client Management
- Track which clients are assigned to each route
- View client addresses and contact information
- See active order status for each client
- Search and filter unassigned clients
- Bulk assignment capabilities

### Address Handling
- Automatic geocoding of client addresses
- Address validation and error reporting
- Support for multiple address formats
- Fallback to client billing address if needed

## 🔧 Customization

### Map Display Options

Customize the map in `mod_routes_navigation.html.twig`:

```javascript
map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 29.7604, lng: -95.3698 },
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    // Add custom styling, controls, etc.
});
```

### Client Address Fields

The extension checks multiple address sources:
1. `service_address` from flag orders
2. `delivery_address` from order config
3. Client's billing address as fallback

Modify `getClientFlagAddress()` in `Service.php` to change this logic.

## 🐛 Troubleshooting

### Common Issues

**"Google Maps JavaScript API Error"**
- Verify API key is correctly entered
- Check that Maps JavaScript API is enabled
- Ensure billing is enabled on your Google Cloud project

**"Failed to calculate route"**
- Verify Directions API is enabled
- Check that client addresses are valid
- Ensure you haven't exceeded API quotas

**Navigation page shows blank map**
- Check browser console for errors
- Verify API key restrictions aren't too strict
- Ensure the Maps JavaScript API is loaded

**📖 For more troubleshooting help, see [TROUBLESHOOTING.md](TROUBLESHOOTING.md)**

## 📁 File Structure

```
Routes/
├── Api/
│   └── Admin.php              # API endpoints
├── Controller/
│   └── Admin.php              # Route controllers
├── html_admin/
│   ├── mod_routes_index.html.twig           # Routes listing
│   ├── mod_routes_route.html.twig           # Route details
│   └── mod_routes_navigation.html.twig      # Navigation page ⭐ NEW
├── Service.php                # Core business logic
├── manifest.json              # Extension metadata
├── navigation.php             # Menu registration
├── GOOGLE_MAPS_SETUP.md       # Setup guide ⭐ NEW
├── CHANGELOG.md               # Version history
└── README.md                  # This file
```

## 🔐 Security

### API Key Protection

1. **Always restrict your API keys**
   - Use HTTP referrer restrictions
   - Limit to specific APIs
   - Monitor usage regularly

2. **Never commit keys to version control**
   - Use environment variables
   - Keep keys in config files that are gitignored

3. **Rotate keys periodically**
   - Create new keys
   - Delete old keys
   - Update production deployments

## 🚦 Roadmap

- [ ] Multi-depot support
- [ ] Time window constraints
- [ ] Driver assignment
- [ ] Route history and tracking
- [ ] Export routes to CSV/PDF
- [ ] Email route to drivers
- [ ] Real-time traffic integration
- [ ] Route templates

## 📄 License

Apache-2.0

## 👨‍💻 Author

Xavier

## 🤝 Support

For issues and questions:
- Review [GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md) for configuration help
- Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for common problems
- Review FOSSBilling logs for error details
- Contact FOSSBilling support for platform-specific issues

## 📚 Additional Documentation

- [GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md) - Complete Google Maps API setup guide
- [CHANGELOG.md](CHANGELOG.md) - Version history and changes
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Common issues and solutions
- [Google Maps Platform Docs](https://developers.google.com/maps/documentation) - Official API documentation
