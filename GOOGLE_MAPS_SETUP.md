# Google Maps API Integration Guide

## Overview

The Routes Extension now uses Google Maps APIs for all geocoding, route optimization, and navigation features. OpenRouteService has been completely removed.

## Features

- **Geocoding**: Automatic address validation and coordinate conversion
- **Route Optimization**: Google's Directions API optimizes the order of stops
- **Turn-by-Turn Navigation**: Full directions displayed on dedicated navigation page
- **Interactive Maps**: Real-time map display with route visualization
- **Mobile Integration**: Direct links to open routes in Google Maps mobile app

## Required APIs

You need to enable the following Google Maps APIs in your Google Cloud Console:

1. **Maps JavaScript API** - For interactive map display
2. **Directions API** - For route optimization and turn-by-turn directions
3. **Geocoding API** - For address validation and coordinate conversion (optional, but recommended)

## Setup Instructions

### Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable billing for the project (required for API usage)

### Step 2: Enable Required APIs

1. In the Google Cloud Console, go to **APIs & Services > Library**
2. Search for and enable each of the following:
   - Maps JavaScript API
   - Directions API
   - Geocoding API (recommended)

### Step 3: Create an API Key

1. Go to **APIs & Services > Credentials**
2. Click **Create Credentials > API Key**
3. Copy the API key that is generated

### Step 4: Restrict Your API Key (Recommended)

For security, restrict your API key:

1. Click on the API key you just created
2. Under **Application restrictions**:
   - Choose "HTTP referrers (web sites)"
   - Add your domain (e.g., `yourdomain.com/*`)
3. Under **API restrictions**:
   - Choose "Restrict key"
   - Select only the APIs you enabled:
     * Maps JavaScript API
     * Directions API
     * Geocoding API
4. Click **Save**

### Step 5: Configure the Extension

1. Open the file: `html_admin/mod_routes_navigation.html.twig`
2. Find the line near the bottom:
   ```html
   <script async defer
       src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap">
   </script>
   ```
3. Replace `YOUR_GOOGLE_MAPS_API_KEY` with your actual API key
4. Save the file

## Pricing

Google Maps APIs use a pay-as-you-go pricing model:

- **Maps JavaScript API**: $7 per 1,000 loads
- **Directions API**: $5 per 1,000 requests
- **Geocoding API**: $5 per 1,000 requests

**Important**: Google provides $200 of free monthly credit, which covers:
- ~28,500 map loads, OR
- ~40,000 directions requests, OR
- ~40,000 geocoding requests

For most small to medium businesses, this free tier is sufficient.

## Usage

### Route Navigation Page

The extension now includes a dedicated route navigation page accessible from:

1. **Routes List**: Click the "Navigate" button next to any route with clients
2. **Route Details Page**: Click "View Route Navigation" button

### Features of Navigation Page

- **Automatic Route Optimization**: Google's Directions API automatically optimizes the order of stops for the most efficient route
- **Interactive Map**: Pan, zoom, and view the entire route
- **Turn-by-Turn Directions**: Detailed step-by-step directions
- **Route Summary**: Shows total distance, estimated duration, and number of stops
- **Mobile Integration**: "Open in Google Maps App" button for navigation on mobile devices

### How Route Optimization Works

1. All assigned client addresses are sent to Google's Directions API
2. The API uses advanced algorithms to determine the most efficient order
3. The `optimizeWaypoints: true` parameter tells Google to reorder stops
4. Results are displayed on an interactive map with detailed directions

## Customization

### Starting Address

The default starting address is hardcoded in the navigation template:
```javascript
startAddress: "15531 Gladeridge Dr, Houston, TX 77068"
```

To change this:
1. Open `html_admin/mod_routes_navigation.html.twig`
2. Find the `routeData` object
3. Update the `startAddress` field

### Map Display Options

You can customize the map appearance by modifying the map initialization in `mod_routes_navigation.html.twig`:

```javascript
map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 29.7604, lng: -95.3698 }, // Houston coordinates
    zoom: 10,
    mapTypeControl: true,
    // Add more options here
});
```

Available options:
- `mapTypeId`: Choose map type (roadmap, satellite, hybrid, terrain)
- `styles`: Custom map styling
- `zoomControl`: Show/hide zoom controls
- `streetViewControl`: Enable/disable street view

## Troubleshooting

### "Google Maps JavaScript API Error"

**Problem**: Error message appears on the navigation page

**Solutions**:
1. Verify your API key is correctly entered in the template
2. Check that Maps JavaScript API is enabled in Google Cloud Console
3. Ensure billing is enabled for your project
4. Check API key restrictions (domain restrictions may be blocking access)

### "Failed to calculate route"

**Problem**: Route optimization fails

**Solutions**:
1. Ensure Directions API is enabled
2. Verify all client addresses are valid
3. Check that you haven't exceeded API quotas
4. Ensure there are at least 2 addresses (start + 1 client minimum)

### API Key Not Working

**Solutions**:
1. Wait a few minutes after creating the key (activation can take time)
2. Check API key restrictions aren't too strict
3. Verify the key has proper permissions for all required APIs
4. Check the browser console for specific error messages

### Addresses Not Found

**Problem**: Some client addresses fail to geocode

**Solutions**:
1. Ensure addresses are complete and accurate
2. Include city, state, and ZIP code
3. Use standard address formats
4. Check for typos in address fields

## API Monitoring

Monitor your API usage in Google Cloud Console:

1. Go to **APIs & Services > Dashboard**
2. Select each API to view usage statistics
3. Set up billing alerts to avoid unexpected charges
4. Review the **Quotas** page to see limits

## Support

For Google Maps API issues:
- [Google Maps Platform Documentation](https://developers.google.com/maps/documentation)
- [Google Maps Platform Support](https://developers.google.com/maps/support)
- [Stack Overflow - google-maps tag](https://stackoverflow.com/questions/tagged/google-maps)

For extension-specific issues:
- Review the TROUBLESHOOTING.md file
- Check FOSSBilling logs for errors
- Ensure all required APIs are enabled

## Security Best Practices

1. **Always restrict your API keys** - Use HTTP referrer restrictions and API restrictions
2. **Never commit API keys to version control** - Use environment variables or config files that are gitignored
3. **Rotate API keys regularly** - Create new keys and delete old ones periodically
4. **Monitor API usage** - Set up billing alerts and review usage regularly
5. **Use separate keys for development and production** - This helps isolate issues and manage costs

## Updates and Maintenance

The Google Maps Platform is regularly updated. To stay current:

1. Subscribe to [Google Maps Platform updates](https://developers.google.com/maps/updates)
2. Review deprecation notices and migration guides
3. Test your implementation after major Google Maps API updates
4. Keep the extension updated to use the latest best practices
