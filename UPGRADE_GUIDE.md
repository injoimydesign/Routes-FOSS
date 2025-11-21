# Upgrade Guide: Version 1.3.1 to 1.4.0

## Overview

Version 1.4.0 introduces major changes to how route navigation works, replacing the previous inline route display with a dedicated navigation page powered by Google Maps Platform.

## What's Changed

### 🗑️ Removed
- OpenRouteService integration (completely removed)
- Client-side geocoding via Nominatim
- Manual nearest-neighbor optimization algorithm
- Inline route display on route details page

### ✅ Added
- Google Maps Platform integration
- Dedicated route navigation page
- Professional route optimization via Google Directions API
- Interactive map display
- Turn-by-turn navigation
- Mobile app integration
- Navigate buttons on routes listing and details pages

## Migration Steps

### 1. Update Files

Replace the following files with the new versions:

**Templates:**
- `html_admin/mod_routes_index.html.twig` (updated)
- `html_admin/mod_routes_route.html.twig` (updated)
- `html_admin/mod_routes_navigation.html.twig` (NEW)

**Controllers:**
- `Controller/Admin.php` (updated with new navigation route)

**Documentation:**
- `README.md` (updated)
- `CHANGELOG.md` (updated)
- `GOOGLE_MAPS_SETUP.md` (NEW)

### 2. Set Up Google Maps API

This is **REQUIRED** for the extension to work in version 1.4.0.

1. **Create a Google Cloud Project**
   - Visit https://console.cloud.google.com/
   - Create a new project
   - Enable billing (required for API access)

2. **Enable These APIs:**
   - Maps JavaScript API
   - Directions API
   - Geocoding API (optional but recommended)

3. **Create an API Key**
   - Go to APIs & Services > Credentials
   - Click "Create Credentials" > "API Key"
   - Copy the generated key

4. **Restrict Your API Key** (Recommended)
   - Application restrictions: HTTP referrers
   - Add your domain(s)
   - API restrictions: Select only the enabled APIs

5. **Configure the Extension**
   - Open `html_admin/mod_routes_navigation.html.twig`
   - Find this line near the bottom:
     ```html
     src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap">
     ```
   - Replace `YOUR_GOOGLE_MAPS_API_KEY` with your actual API key
   - Save the file

### 3. Update Starting Address (Optional)

If your starting location is different from the default:

1. Open `html_admin/mod_routes_navigation.html.twig`
2. Find the `routeData` object
3. Update the `startAddress` field:
   ```javascript
   startAddress: "Your Address Here"
   ```

### 4. Test the Integration

1. Log in to FOSSBilling admin
2. Go to Routes
3. Click on any route with assigned clients
4. Click "View Route Navigation"
5. Verify the map loads and shows the route
6. Check that turn-by-turn directions appear
7. Test the "Open in Google Maps App" button

## Cost Considerations

### Free Tier
Google provides **$200 of free credit per month**, which covers:
- ~28,500 map loads
- ~40,000 directions requests
- ~40,000 geocoding requests

### Pricing
- Maps JavaScript API: $7 per 1,000 loads
- Directions API: $5 per 1,000 requests
- Geocoding API: $5 per 1,000 requests

**For most small to medium businesses, you will stay within the free tier.**

### Cost Optimization Tips

1. **Cache Route Data**: Don't reload the navigation page unnecessarily
2. **Batch Operations**: Plan routes during low-traffic times
3. **Monitor Usage**: Set up billing alerts in Google Cloud Console
4. **Restrict API Keys**: Prevent unauthorized usage

## Breaking Changes

### 1. No More Inline Route Display
The "View Optimized Route" button on the route details page now redirects to a dedicated navigation page instead of showing the route inline.

**Migration:** Update any custom code or links that expected inline display.

### 2. OpenRouteService Removed
All OpenRouteService-related code has been removed.

**Migration:** If you had any custom OpenRouteService integrations, they will no longer work. You'll need to adapt them to use Google Maps APIs.

### 3. JavaScript Changes
The route optimization JavaScript has been completely rewritten.

**Migration:** Any custom modifications to the optimization code will need to be re-implemented using the new Google Maps APIs.

## Troubleshooting Upgrade Issues

### Issue: Navigation page shows blank

**Solution:**
1. Verify API key is correctly entered
2. Check browser console for error messages
3. Ensure Maps JavaScript API is enabled
4. Verify billing is enabled on your Google Cloud project

### Issue: "Google Maps JavaScript API Error: ApiNotActivatedMapError"

**Solution:**
1. Go to Google Cloud Console
2. Navigate to APIs & Services > Library
3. Search for "Maps JavaScript API"
4. Click "Enable"
5. Repeat for "Directions API"

### Issue: Navigation works but directions don't appear

**Solution:**
1. Ensure Directions API is enabled
2. Check that all client addresses are valid
3. Review browser console for API errors
4. Verify you haven't exceeded API quotas

### Issue: "This page can't load Google Maps correctly"

**Solution:**
1. Check that billing is enabled on your project
2. Verify API key restrictions aren't too strict
3. Ensure your domain is added to allowed referrers
4. Check that the key hasn't expired or been revoked

## Rollback Instructions

If you need to revert to version 1.3.1:

1. Restore the old template files from backup
2. Restore the old Controller/Admin.php
3. Remove the navigation route registration
4. Remove GOOGLE_MAPS_SETUP.md

**Note:** Version 1.3.1 also used Google Maps (for the URL), but didn't require an API key. Version 1.4.0 requires an API key because it uses the Maps JavaScript API and Directions API.

## Getting Help

If you encounter issues during the upgrade:

1. Review [GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md)
2. Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
3. Review Google Cloud Console for API errors
4. Check FOSSBilling error logs
5. Verify all API credentials are correct

## Benefits of Upgrading

- **Better Route Optimization**: Google's algorithms are far superior
- **Professional Navigation**: Industry-standard turn-by-turn directions
- **Mobile Integration**: Seamless handoff to Google Maps app
- **Interactive Maps**: Pan, zoom, and explore routes
- **Reliability**: 99.9% uptime SLA from Google
- **Real-time Updates**: Support for traffic data (with additional setup)
- **Cleaner UI**: Dedicated navigation page instead of inline display

## Timeline

**Recommended upgrade timeline:**
1. Day 1: Set up Google Cloud account and create API key
2. Day 2: Update files and configure API key
3. Day 3: Test with a few routes
4. Day 4: Deploy to production
5. Day 5: Monitor usage and costs

## Support

For assistance:
- Google Maps Platform: https://developers.google.com/maps/support
- FOSSBilling: https://fossbilling.org/docs
- Extension Issues: Check GitHub repository or contact developer

## Conclusion

Version 1.4.0 represents a significant upgrade to the route management system. While it requires setting up Google Maps API credentials, the benefits far outweigh the setup effort. The free tier is generous enough for most users, and the professional-grade navigation features will greatly improve your routing workflow.
