# Routes Extension - Quick Installation Guide

## Installation Steps

### 1. Upload Files

Extract the ZIP and upload the `routes_extension` folder (rename to just `Routes`) to:
```
/library/Box/Mod/Routes/
```

**Important**: The folder MUST be named `Routes` (capital R), not `routes_extension`.

### 2. Verify File Structure

Your structure should look like:
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

### 3. Set Permissions

```bash
chmod 755 library/Box/Mod/Routes
chmod 755 library/Box/Mod/Routes/Controller
chmod 755 library/Box/Mod/Routes/Api
chmod 755 library/Box/Mod/Routes/html_admin
chmod 644 library/Box/Mod/Routes/*.php
chmod 644 library/Box/Mod/Routes/Controller/*.php
chmod 644 library/Box/Mod/Routes/Api/*.php
chmod 644 library/Box/Mod/Routes/html_admin/*.twig
```

### 4. Activate Extension

1. Log in to FOSSBilling admin panel
2. Go to **Extensions** → **Overview**
3. Find "Routes" in the list
4. Click **Activate**

### 5. Clear Cache

```bash
rm -rf data/cache/*
```

Or in admin panel: **System** → **Settings** → **Clear Cache**

### 6. Access Routes

The Routes page will be available in your admin menu, or directly at:
```
/admin/routes
```

## If You Get a 404 Error

See [TROUBLESHOOTING.md](TROUBLESHOOTING.md) for detailed solutions.

Quick fixes:
1. Verify folder is named `Routes` (not `routes_extension`)
2. Clear cache after activation
3. Check file permissions
4. Verify extension is activated in Extensions panel

## First Use

1. Click "Create New Route" to add your first route
2. Enter a route name and optional description
3. Click on the route name to view details
4. Assign clients from the "Unassigned Clients" table

## Support

For issues, see:
- [README.md](README.md) - Full documentation
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Detailed troubleshooting
