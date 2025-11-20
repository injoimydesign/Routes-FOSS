# Technical Notes - Database Implementation

## Database Approach - Version 1.1.1+

As of version 1.1.1, the Routes extension uses **raw SQL queries** for all database operations instead of RedBeanPHP ORM. This ensures maximum compatibility with FOSSBilling's database layer.

## Why Raw SQL?

After testing with RedBeanPHP, we encountered compatibility issues:
- `lastInsertId()` method not consistently available
- RedBeanPHP's table recognition issues with manually-created tables
- Inconsistent behavior across different FOSSBilling installations

Raw SQL provides:
- 100% compatibility with FOSSBilling's database layer
- Predictable behavior across all installations
- Better performance for simple CRUD operations
- Easier debugging and maintenance

## Current Implementation

### Creating Records

```php
$sql = "INSERT INTO routes (name, description, created_at, updated_at) 
        VALUES (:name, :description, NOW(), NOW())";
$db->exec($sql, ['name' => $name, 'description' => $description]);

// Get the last inserted ID
$result = $db->getRow("SELECT LAST_INSERT_ID() as id");
$id = (int)$result['id'];
```

### Reading Records

```php
// Single record
$sql = "SELECT * FROM routes WHERE id = :id";
$route = $db->getRow($sql, ['id' => $id]);

// Multiple records with conditions
$sql = "SELECT * FROM client_routes WHERE route_id = :route_id";
$assignments = $db->getAll($sql, ['route_id' => $route_id]);
```

### Updating Records

```php
$sql = "UPDATE routes 
        SET name = :name, description = :description, updated_at = NOW()
        WHERE id = :id";
$db->exec($sql, [
    'id' => $id,
    'name' => $name,
    'description' => $description
]);
```

### Deleting Records

```php
$sql = "DELETE FROM routes WHERE id = :id";
$db->exec($sql, ['id' => $id]);
```

## FOSSBilling Database Methods

The extension uses these FOSSBilling database methods:

- **`exec($sql, $params)`** - Execute INSERT, UPDATE, DELETE queries
- **`getRow($sql, $params)`** - Get a single row
- **`getAll($sql, $params)`** - Get multiple rows
- **`getOne($sql, $params)`** - Get a single value

All parameters are safely bound to prevent SQL injection.

## Best Practices

### Always Use Parameter Binding

**Good:**
```php
$db->exec("DELETE FROM routes WHERE id = :id", ['id' => $id]);
```

**Bad:**
```php
$db->exec("DELETE FROM routes WHERE id = $id"); // SQL injection risk!
```

### Use NOW() for Timestamps

```php
$sql = "INSERT INTO routes (name, created_at, updated_at) 
        VALUES (:name, NOW(), NOW())";
```

This ensures consistent timezone handling by the database.

### Check Return Values

```php
$result = $db->getRow("SELECT LAST_INSERT_ID() as id");
if (!$result || !isset($result['id'])) {
    throw new \Exception('Failed to create route');
}
```

### Handle Errors Gracefully

```php
try {
    $db->exec($sql, $params);
    return true;
} catch (\Exception $e) {
    error_log('Database error: ' . $e->getMessage());
    throw new \Exception('Operation failed');
}
```

## Migration Notes

If you're updating from version 1.0.2:
- All RedBeanPHP calls have been replaced with raw SQL
- Functionality remains exactly the same
- No database schema changes required
- Simply update the files and you're done

## Complex Queries

For complex queries with JOINs, we continue using raw SQL:

## Complex Queries

For complex queries with JOINs, we continue using raw SQL:

```php
$sql = "
    SELECT r.*, COUNT(cr.client_id) as client_count
    FROM routes r
    LEFT JOIN client_routes cr ON r.id = cr.route_id
    GROUP BY r.id
";
$routes = $db->getAll($sql);
```

This is the recommended approach for reporting/analytics queries.

## Performance Considerations

Raw SQL queries are:
- Fast and efficient
- Easy to optimize
- Predictable in performance
- Well-supported by MySQL query optimizer

For bulk operations, raw SQL is often faster than ORM approaches.

## Security

All queries use parameter binding:
```php
$db->exec($sql, ['id' => $id, 'name' => $name]);
```

FOSSBilling's database layer automatically:
- Escapes parameters
- Prevents SQL injection
- Handles data types correctly

## References

- FOSSBilling Database Documentation
- MySQL/MariaDB Documentation: https://dev.mysql.com/doc/
- SQL Best Practices
