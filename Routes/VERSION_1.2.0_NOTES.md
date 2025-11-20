# Version 1.2.0 Update - Active Order Status

## 🎯 What's New

### Active Order Status Column

Both the **Assigned Clients** and **Unassigned Clients** tables now display an "Active Order" status column showing whether each client has an active flag order.

## 📊 Visual Indicators

**Green Badge - Active Order:**
```
✓ Active
✓ Pending Setup
```

**Gray Badge - No Active Order:**
```
No Active Order
```

## 🔍 How It Works

The system checks for flag orders with these statuses:
- `active`
- `pending_setup`

If a client has at least one flag order with either of these statuses, they're marked as having an active order.

## 📍 Address Field Updates

The address retrieval has been improved to use specific fields:

**Priority Order:**
1. **service_address + service_city + service_zip_code** (combined)
   - Example: "123 Main St, Houston, 77001"
2. **delivery_address** (fallback)
3. **address** (fallback)
4. **Client's default address** (final fallback)

## 💡 Use Cases

### Route Planning
- Quickly identify which clients need service
- Prioritize routes with active orders
- Skip routes with no active orders

### Client Management
- See at a glance which clients are currently active
- Identify clients who may need follow-up
- Filter or prioritize based on order status

### Operational Efficiency
- Plan routes only for clients with active orders
- Reduce unnecessary stops
- Optimize driver schedules

## 📱 Where to See It

### Assigned Clients Table
Navigate to any route to see all assigned clients with their order status.

### Unassigned Clients Table
Scroll down on any route page to see unassigned clients and their order status.

## 🎨 Visual Design

**Active Order Badge:**
- Green background (#5cb85c)
- Checkmark icon (✓)
- Shows status text (Active/Pending Setup)
- Hover shows full status details

**No Active Order Badge:**
- Gray background (#6c757d)
- "No Active Order" text
- Indicates client can be skipped if needed

## 🔧 Technical Details

### Database Queries
New subquery added to retrieve order status:
```sql
SELECT co.status
FROM client_order co
WHERE co.client_id = c.id
AND co.product_id IN (
    SELECT p.id FROM product p WHERE p.title LIKE '%flag%'
)
AND co.status IN ('active', 'pending_setup')
ORDER BY co.created_at DESC
LIMIT 1
```

### Client Data Structure
Each client object now includes:
- `active_order_status` - The status string if active order exists
- `has_active_order` - Boolean flag for easy checking

## 🚀 Benefits

**Better Planning:**
- See which clients need service immediately
- Plan routes more efficiently
- Avoid unnecessary stops

**Improved Communication:**
- Know which clients to contact
- Identify clients needing follow-up
- Better customer service

**Time Savings:**
- Quick visual identification
- No need to check each client individually
- Faster route preparation

## 📋 Example Usage

### Scenario: Planning Today's Route

1. Open your morning route
2. Check "Active Order" column
3. See 8 clients with active orders (green badges)
4. See 3 clients with no active orders (gray badges)
5. Focus on the 8 active clients
6. Optimize route for just those addresses

### Scenario: Identifying Follow-up Needs

1. View unassigned clients
2. Filter mentally for "No Active Order" badges
3. Identify clients who haven't ordered in a while
4. Reach out for renewals or follow-up

## 🔄 Compatibility

- Works with existing routes
- No database migration needed
- Automatically detects order status
- Compatible with all previous versions

## 📝 Notes

**Order Status Detection:**
- Only checks flag orders (products with "flag" in title)
- Considers "active" and "pending_setup" statuses
- Uses most recent order if multiple exist
- Real-time status (no caching)

**Performance:**
- Efficient SQL subqueries
- Minimal performance impact
- Scales well with large client lists
- No additional page load time

## 🎓 Tips

1. **Use for route planning** - Focus on clients with active orders
2. **Identify gaps** - Gray badges show clients to follow up with
3. **Optimize stops** - Skip clients without active orders if needed
4. **Track renewals** - Monitor when clients become inactive

## 📚 Related Documentation

- **CHANGELOG.md** - Full version history
- **README.md** - Complete feature documentation
- **Service.php** - Technical implementation details

## 🆙 Upgrading

Simply replace the extension files with v1.2.0 - no additional steps required!

All existing data and routes remain unchanged.
