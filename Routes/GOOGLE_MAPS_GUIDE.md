# Version 1.3.0 - Google Maps Integration

## 🗺️ What Changed

The route optimization now uses **Google Maps** instead of OpenRouteService and starts all routes from your location at **15531 Gladeridge Dr, Houston, TX 77068**.

## ✨ Key Features

### Fixed Starting Point
- All routes now start from: **15531 Gladeridge Dr, Houston, TX 77068**
- System geocodes this address first
- Route optimization finds nearest client from your location
- More realistic routing for actual deliveries

### Google Maps Integration
- Direct integration with Google Maps Directions
- Click "Open in Google Maps" for turn-by-turn navigation
- Works on desktop and mobile devices
- Full Google Maps features available (traffic, alternate routes, etc.)

## 📍 Starting Address

**Your Location:**
```
15531 Gladeridge Dr
Houston, TX 77068
```

This address is hardcoded into the system and will be used as the starting point for all routes.

## 🎯 How Routes Are Optimized

1. **Geocode starting address** - 15531 Gladeridge Dr
2. **Geocode all client addresses** - With 1-second delay between requests
3. **Find nearest client** - From starting location
4. **Continue optimization** - Always choosing nearest unvisited client
5. **Generate Google Maps URL** - With all waypoints in optimal order

## 📊 What You'll See

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
```

## 🔗 Google Maps URL

The system generates a Google Maps URL like:
```
https://www.google.com/maps/dir/?api=1
  &origin=15531+Gladeridge+Dr,+Houston,+TX+77068
  &destination=789+Pine+Rd,+Houston,+TX
  &waypoints=123+Main+St|456+Oak+Ave
  &travelmode=driving
```

## ⚠️ Limitations

### Google Maps Waypoint Limit
- **Maximum 9 waypoints** between origin and destination
- Routes with 10+ clients will only show first 9 in Google Maps
- System will still optimize all stops, but URL may not include all

**Workaround for large routes:**
Split routes into smaller segments:
- Route 1: Starting point + clients 1-9
- Route 2: Last client from Route 1 + clients 10-18
- Etc.

## 🚀 Benefits

### More Accurate Routes
- Starts from your actual location
- Considers drive time from home/office
- Better total distance estimates
- More realistic time calculations

### Real-World Navigation
- Use Google Maps on phone for GPS navigation
- Real-time traffic updates
- Alternate route suggestions
- Voice-guided turn-by-turn directions

### Familiar Interface
- Most drivers already know Google Maps
- No learning curve
- Works with Android Auto / Apple CarPlay
- Save routes to Google Maps account

## 💡 Usage Tips

### Planning Your Day
1. Open route in FOSSBilling
2. Click "View Optimized Route"
3. Review the stop order
4. Click "Open in Google Maps"
5. Start navigation on your phone

### For Drivers
1. Share the Google Maps link with drivers
2. Link opens directly in navigation mode
3. Driver can start/stop navigation as needed
4. Works offline after route is downloaded

### Multiple Routes Per Day
- Optimize morning route from starting address
- Later routes can start from last stop of previous route
- Consider creating separate routes for AM/PM

## 🔧 Technical Details

### Starting Address Configuration
The starting address is hardcoded in the template file:
```javascript
const STARTING_ADDRESS = '15531 Gladeridge Dr, Houston, TX 77068';
```

### Google Maps API Usage
- Uses Google Maps URL scheme (no API key required)
- Free for personal/business use
- No request limits
- Works in any web browser

### Geocoding
- Still uses OpenStreetMap Nominatim (free)
- Geocodes starting address first
- Then geocodes all client addresses
- 1-second delay between requests

## 📱 Mobile Usage

### On Driver's Phone
1. Open the Google Maps link
2. Link opens in Google Maps app (if installed)
3. Or opens in mobile browser
4. Tap "Start" to begin navigation
5. Follow turn-by-turn directions

### Offline Support
- Google Maps can download route for offline use
- Useful in areas with poor cell coverage
- Download route before leaving

## 🔄 Changing Starting Address

If you need to change the starting address:

1. Open `/html_admin/mod_routes_route.html.twig`
2. Find the line:
   ```javascript
   const STARTING_ADDRESS = '15531 Gladeridge Dr, Houston, TX 77068';
   ```
3. Replace with your desired address
4. Save and upload the file
5. Clear browser cache

## 🎓 Best Practices

### Route Planning
- Review optimized route before starting
- Check for any obvious inefficiencies
- Consider one-way streets and traffic patterns
- Plan routes during off-peak hours when possible

### Address Quality
- Ensure all client addresses are complete and accurate
- Include city and ZIP code
- Use standard address format
- Test new addresses before adding to routes

### Route Size
- Optimal: 5-10 stops per route
- Maximum: 9 stops (Google Maps waypoint limit)
- For 10+ clients, split into multiple routes
- Consider geographic clustering

## 📞 Support

If you need help:
- Check ROUTE_OPTIMIZATION.md for detailed guide
- Review TROUBLESHOOTING.md for common issues
- Verify starting address is correct
- Test with small route (2-3 clients) first

## 🆙 Upgrading from Previous Version

No special steps needed:
1. Replace extension files with v1.3.0
2. Clear browser cache
3. Test route optimization
4. All existing routes and data remain unchanged

The starting address is now active immediately!
