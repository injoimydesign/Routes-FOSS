# Route Optimization Guide

## Overview

The Routes extension includes powerful route optimization features that help you plan the most efficient delivery routes for your clients starting from **15531 Gladeridge Dr, Houston, TX 77068**. This feature integrates with OpenStreetMap and Google Maps to provide:

- Automatic address geocoding
- Optimized route ordering from your starting location
- Distance calculations
- Time estimates
- Turn-by-turn directions via Google Maps

## How It Works

### 1. Address Geocoding

When you click "View Optimized Route", the system:
- Geocodes your starting address: 15531 Gladeridge Dr, Houston, TX 77068
- Collects all addresses from clients assigned to the route
- Sends each address to OpenStreetMap's Nominatim geocoding service
- Converts addresses to latitude/longitude coordinates
- Handles rate limiting (1 second delay between requests)

### 2. Route Optimization

The extension uses a **Nearest Neighbor Algorithm** starting from your location:
- Starts at 15531 Gladeridge Dr, Houston, TX 77068
- Finds the closest unvisited client address
- Continues until all addresses are visited
- Calculates distances using the Haversine formula

This provides a good approximation of the optimal route without requiring complex API calls or API keys.

### 3. Distance and Time Calculation

**Distance:**
- Uses the Haversine formula to calculate great-circle distances
- Provides accurate distance in kilometers between each stop
- Sums total route distance from starting point

**Time Estimation:**
- Assumes average speed of 30 km/h (accounting for stops, traffic, etc.)
- Provides estimated hours and minutes
- Note: This is an estimate; actual time may vary

### 4. Google Maps Integration

After optimization, you can:
- View the optimized order directly in FOSSBilling
- Click "Open in Google Maps" to see the route on a map
- Get turn-by-turn directions
- Use Google Maps navigation on mobile devices

## Using the Feature

### Step-by-Step

1. **Navigate to a route** that has clients with valid addresses
2. **Click "View Optimized Route"** button
3. **Wait** while starting address and client addresses are geocoded (typically 5-10 seconds)
4. **Review** the optimized route order starting from 15531 Gladeridge Dr
5. **Click "Open in Google Maps"** for detailed directions

### What You'll See

**Optimized Route Display:**
```
1. Starting Point
   15531 Gladeridge Dr, Houston, TX 77068
   (Your location)

2. John Smith
   123 Main St, Houston, TX 77001
   ↑ 2.34 km from previous stop

3. Jane Doe
   456 Oak Ave, Houston, TX 77002
   ↑ 1.87 km from previous stop

4. Bob Johnson
   789 Pine Rd, Houston, TX 77003
   ↑ 1.45 km from previous stop
```

**Route Statistics:**
- Total Distance: 15.42 km (from starting point through all stops)
- Estimated Duration: 31 minutes

## Technical Details

### APIs Used

**OpenStreetMap Nominatim (Geocoding):**
- URL: `https://nominatim.openstreetmap.org/search`
- Rate Limit: 1 request per second
- Free to use, no API key required
- User-Agent: `FOSSBilling-Routes-Extension`

**Google Maps (Directions):**
- URL: `https://www.google.com/maps/dir/?api=1`
- Uses Google Maps Directions API URL scheme
- Opens in browser with full navigation features
- Free to use for viewing routes (no API key needed for this usage)

### Algorithm Details

**Nearest Neighbor Optimization:**
```
1. Start at 15531 Gladeridge Dr, Houston, TX 77068
2. While unvisited locations exist:
   a. Find closest unvisited location
   b. Move to that location
   c. Mark as visited
3. Return ordered route
```

### Google Maps URL Format

The system generates a Google Maps URL with:
- Origin: 15531 Gladeridge Dr, Houston, TX 77068
- Destination: Last client address
- Waypoints: All addresses in between (up to 9 waypoints supported)
- Travel mode: Driving

**Haversine Distance Formula:**
```javascript
R = 6371 km (Earth's radius)
Δlat = lat2 - lat1
Δlon = lon2 - lon1
a = sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)
c = 2 × atan2(√a, √(1-a))
distance = R × c
```

## Limitations and Considerations

### Address Quality
- Addresses must be complete and accurate for geocoding
- Incomplete addresses may fail to geocode
- Non-standard formats may produce unexpected results

### Rate Limiting
- Nominatim has a 1 request/second limit
- Routes with many addresses will take longer to process
- System automatically adds delays to respect limits

### Optimization Algorithm
- Nearest Neighbor is a heuristic, not guaranteed optimal
- Works well for most practical cases
- For perfect optimization, would need traveling salesman solver
- Good enough for 90%+ of use cases

### Internet Dependency
- Requires active internet connection
- Geocoding happens in real-time
- No offline capability

## Troubleshooting

### "Could not geocode enough addresses"

**Causes:**
- Addresses are incomplete
- Addresses are invalid
- Rate limiting issues
- Network connectivity problems

**Solutions:**
- Verify all client addresses are complete
- Check address format (include city, state, zip)
- Wait and try again if rate limited
- Check internet connection

### Addresses in Wrong Order

**Causes:**
- Geocoding produced incorrect coordinates
- Addresses are very close together
- Starting point affects optimization

**Solutions:**
- Verify address accuracy
- Manually adjust in OpenRouteService if needed
- Consider reordering first address if needed

### OpenRouteService Link Not Working

**Causes:**
- Too many waypoints for URL (Google Maps supports up to 9 waypoints)
- Special characters in addresses
- Browser blocking popup

**Solutions:**
- For routes with 10+ stops, Google Maps may not display all waypoints
- Check for unusual characters in addresses
- Allow popups from FOSSBilling domain
- For very large routes, consider splitting into multiple smaller routes

### Google Maps Not Opening

**Causes:**
- Browser blocking popups
- Internet connectivity issues
- Too many waypoints (>9)

**Solutions:**
- Allow popups in browser settings
- Check internet connection
- Split large routes into smaller segments (under 10 stops each)
- Try opening the link in a new tab manually

## Future Enhancements

Potential improvements for future versions:

1. **API Key Support**: Allow users to add OpenRouteService API key for:
   - Real optimization API calls
   - Avoidance options (tolls, highways, etc.)
   - Multiple vehicle types

2. **Starting Point**: Let users specify a starting address

3. **Waypoint Constraints**: Force certain stops to be in specific order

4. **Multiple Routes**: Split large routes into smaller optimized segments

5. **Save Optimizations**: Store optimized order back to database

6. **Real-time Traffic**: Integrate traffic data for better time estimates

7. **Route History**: Track which routes have been driven

8. **Mobile App**: Native mobile app for drivers

## Best Practices

### Address Management
- Keep addresses updated and complete
- Use standardized format (Street, City, State ZIP)
- Verify new addresses before assigning to routes
- Clean up invalid addresses regularly

### Route Size
- Optimal: 5-15 stops per route
- Maximum: ~25 stops (due to URL length limits)
- Too many stops = harder to optimize and manage

### Regular Optimization
- Re-optimize routes when clients are added/removed
- Check optimization before each delivery day
- Adjust based on driver feedback

### Driver Communication
- Share the OpenRouteService link with drivers
- Provide printed route lists as backup
- Allow drivers to report address issues

## Support and Resources

- **OpenStreetMap**: https://www.openstreetmap.org/
- **Nominatim API**: https://nominatim.org/
- **OpenRouteService**: https://openrouteservice.org/
- **Haversine Formula**: https://en.wikipedia.org/wiki/Haversine_formula

## Privacy Considerations

- Addresses are sent to OpenStreetMap for geocoding
- No personal information beyond addresses is transmitted
- Nominatim's privacy policy: https://operations.osmfoundation.org/policies/nominatim/
- OpenRouteService's privacy policy: https://openrouteservice.org/terms-of-service/

Client names and other details stay within your FOSSBilling system and are not transmitted to external services.
