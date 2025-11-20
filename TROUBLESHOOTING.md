# Routes Extension - Troubleshooting Guide

## 404 Error Fix

If you're getting a 404 error when accessing the Routes page, follow these steps:

### 1. Verify Installation Location

The extension must be installed at:
```
/library/Box/Mod/Routes/
```

Not in `/modules/` or any other location.

### 2. Check File Structure

Ensure your file structure looks like this:
```
/library/Box/Mod/Routes/
├── manifest.json
├── Service.php
├── navigation.php
├── Controller/
│   └── Admin.php
├── Api/
│   └── Admin.php
└── html_admin/
    ├── index.html.twig
    ├── mod_routes_index.html.twig
    ├── route.html.twig
    └── mod_routes_route.html.twig
```

### 3. Activate the Extension

1. Go to **Extensions** → **Overview** in FOSSBilling admin
2. Find "Routes" in the list
3. Click **Activate**
4. Wait for confirmation message

### 4. Clear Cache

After activation, clear the FOSSBilling cache:

```bash
# From your FOSSBilling root directory
rm -rf data/cache/*
```

Or use the admin panel:
- Go to **System** → **Settings**
- Click **Clear Cache**

### 5. Check URL Structure

The correct URLs should be:
- Main page: `/admin/routes` or `/admin/routes/index`
- Route details: `/admin/routes/route/1` (where 1 is the route ID)

### 6. Verify Database Tables

After activation, verify the tables were created:

```sql
SHOW TABLES LIKE 'routes';
SHOW TABLES LIKE 'client_routes';
```

If tables don't exist, manually run:

```sql
CREATE TABLE IF NOT EXISTS `routes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `client_routes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `client_id` INT(11) NOT NULL,
    `route_id` INT(11) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `client_id` (`client_id`),
    KEY `route_id` (`route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 7. Check File Permissions

Ensure proper permissions:

```bash
# From FOSSBilling root
chmod 755 library/Box/Mod/Routes
chmod 755 library/Box/Mod/Routes/Controller
chmod 755 library/Box/Mod/Routes/Api
chmod 755 library/Box/Mod/Routes/html_admin
chmod 644 library/Box/Mod/Routes/*.php
chmod 644 library/Box/Mod/Routes/Controller/*.php
chmod 644 library/Box/Mod/Routes/Api/*.php
chmod 644 library/Box/Mod/Routes/html_admin/*.twig
```

### 8. Check Error Logs

Look in FOSSBilling's error log for more details:

```bash
tail -f data/log/application.log
```

Common errors and solutions:

**Error: "Class not found"**
- Solution: Ensure namespace is correct in all PHP files
- Check autoloader by deactivating and reactivating extension

**Error: "Template not found"**
- Solution: Verify template files exist in `html_admin/` folder
- Check template names match what Controller is calling

**Error: "Method does not exist"**
- Solution: Verify API methods are properly defined in `Api/Admin.php`
- Check method names match what Controller is calling

**Error: "Call to undefined method Box_AppAdmin::getRequestParam()"**
- Solution: This has been fixed in version 1.0.1+
- Use `$this->di['request']->request->get('param_name')` instead
- Download the latest version of the extension

**Error: "Call to undefined method Box_Database::lastInsertId()"**
- Solution: This has been fixed in version 1.0.2+
- Extension now uses RedBeanPHP ORM methods (dispense, store, load, trash)
- Download the latest version of the extension

### 9. Verify Menu Entry

After activation, you should see "Routes" in the admin menu. If not:

1. Check `navigation.php` exists
2. Try accessing directly via `/admin/routes`
3. Check browser console for JavaScript errors

### 10. Test Basic Functionality

Once you can access the page:

1. Try creating a route
2. If that works, the extension is properly installed
3. If you get errors, check the error log

## Still Having Issues?

### Enable Debug Mode

In your FOSSBilling config file (`config.php`), enable debug:

```php
'debug' => true,
'log_exceptions' => true,
```

### Manual URL Test

Try accessing these URLs directly:
- `/admin/routes`
- `/admin/routes/index`

If neither works, the routing is not registered properly.

### Re-install Steps

1. Deactivate the extension
2. Delete the `/library/Box/Mod/Routes/` folder
3. Re-upload all files
4. Activate again
5. Clear cache

### Common Causes of 404

1. **Wrong Installation Path**: Must be in `/library/Box/Mod/Routes/`
2. **Extension Not Activated**: Check Extensions page
3. **Cache Issues**: Clear cache after activation
4. **Routing Not Registered**: Controller's `register()` method has issues
5. **Namespace Issues**: PHP namespaces incorrect
6. **Template Names**: Template files named incorrectly

## Getting Help

If issues persist:

1. Check FOSSBilling version (requires 4.22+)
2. Verify PHP version (requires 7.4+)
3. Check MySQL version (requires 5.7+)
4. Review complete error log
5. Test with a different extension to rule out system issues

## Quick Diagnostic Commands

```bash
# Check if extension folder exists
ls -la library/Box/Mod/Routes/

# Check if templates exist
ls -la library/Box/Mod/Routes/html_admin/

# View error log
tail -100 data/log/application.log

# Check database tables
mysql -u [user] -p [database] -e "SHOW TABLES LIKE 'routes%';"
```
