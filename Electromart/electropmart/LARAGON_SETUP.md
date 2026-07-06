# Electropmart - Laragon Local Development Setup Guide

Complete guide to set up and run Electropmart with Laragon for local development.

## What is Laragon?

Laragon is a portable, isolated, fast & powerful local development environment for PHP/Laravel development on Windows. It includes:
- Apache Web Server
- PHP (multiple versions available)
- MySQL
- Node.js
- Composer
- Git
- And more...

Download: [https://laragon.org](https://laragon.org)

---

## Installation & Setup

### Step 1: Install Laragon

1. Download Laragon from [https://laragon.org/download](https://laragon.org/download)
2. Extract to any folder (e.g., `C:\Laragon`)
3. Run `laragon.exe`
4. Click "Start All" to start services

### Step 2: Verify Installation

1. Open browser and go to `http://localhost`
2. You should see Laragon's welcome page
3. Check that Apache and MySQL are running (green indicators in Laragon)

### Step 3: Add Electropmart to Laragon

1. **Extract Electropmart files to Laragon's www folder:**
   ```
   C:\Laragon\www\electropmart\
   ```

2. **Folder structure should look like:**
   ```
   C:\Laragon\
   ├── www\
   │   └── electropmart\
   │       ├── admin\
   │       ├── api\
   │       ├── assets\
   │       ├── index.php
   │       ├── config.php
   │       ├── database.sql
   │       └── ... (other files)
   │   └── laravel\ (example)
   ├── bin\
   ├── data\
   └── ... (other folders)
   ```

### Step 4: Create Database

1. **Open phpMyAdmin:**
   - In Laragon, right-click the MySQL icon
   - Select "MySQL > phpMyAdmin"
   - Or go to: `http://localhost/phpmyadmin`

2. **Create new database:**
   - Click "New" on the left sidebar
   - Database name: `electropmart`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. **Import database schema:**
   - Select the `electropmart` database
   - Click "Import" tab
   - Click "Choose File"
   - Select `database.sql` from the electropmart folder
   - Click "Go"

### Step 5: Configure Database

1. **Edit config.php:**
   ```
   C:\Laragon\www\electropmart\config.php
   ```

2. **Update database credentials:**
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // Laragon default is empty
   define('DB_NAME', 'electropmart');
   define('SITE_URL', 'http://localhost/electropmart/');
   ```

3. **Save the file**

### Step 6: Start Using Electropmart

1. **Access the application:**
   - Frontend: `http://localhost/electropmart/`
   - Admin Panel: `http://localhost/electropmart/admin/`

2. **Create admin account:**
   - Go to: `http://localhost/electropmart/register.php`
   - Create a new account (e.g., username: admin, email: admin@test.com)
   - Open phpMyAdmin
   - Go to `electropmart > users` table
   - Find your user account
   - Change `role` from 'customer' to 'admin'
   - Click "Go" to save
   - Logout and login again

3. **Access admin dashboard:**
   - Login with your admin account
   - Click "Admin Panel" in the navigation
   - Or go directly to: `http://localhost/electropmart/admin/`

---

## Laragon Features & Commands

### Virtual Host Setup

If you want a custom domain (optional):

1. **Edit Laragon hosts:**
   - Go to: `C:\Laragon\etc\hosts`
   - Add line: `127.0.0.1 electropmart.test`

2. **Edit Laragon apache config:**
   - Right-click Laragon > Preferences
   - Under "Apache > Vhosts", add entry
   - Or edit: `C:\Laragon\etc\apache2\conf.d\vhosts.conf`

3. **Add VirtualHost:**
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/Laragon/www/electropmart"
       ServerName electropmart.test
   </VirtualHost>
   ```

4. **Restart Apache and access:**
   - `http://electropmart.test/`

### Terminal/Console

Laragon includes a terminal for running commands:

1. Click "Terminal" in Laragon menu
2. Or right-click anywhere in `www` folder and select "Open Terminal"
3. Run commands:
   ```bash
   # Common commands
   php -v                    # Check PHP version
   mysql -u root            # Access MySQL
   composer install         # Install PHP dependencies
   npm install             # Install Node packages
   ```

### Web Root Access

1. Right-click Laragon > "Menu > Tools > Open Terminal"
2. Or navigate directly: `C:\Laragon\www\`
3. Or click "Open File" in Laragon menu

---

## Development Workflow

### Adding New Products

1. Login as admin
2. Go to Admin Panel > Manage Products
3. Click "+ Add New Product"
4. Fill in details:
   - Product Name
   - Category
   - Price & Discount Price
   - Stock Quantity
   - Description
   - Image URL
   - Mark as Featured (optional)
5. Click "Add Product"

### Managing Orders

1. Admin Panel > View Orders
2. Click on an order to view details
3. Update order status:
   - Pending → Confirmed → Processing → Shipped → Delivered
4. Update payment status
5. Click "Update Status"

### Managing Categories

1. Admin Panel > Manage Categories
2. Add/Edit/Delete categories
3. Set category images for better display

### Managing Users

1. Admin Panel > Manage Users
2. View all registered customers
3. Edit user roles (promote to admin if needed)
4. Enable/disable user accounts

---

## Troubleshooting

### Database Connection Error

**Problem:** "Connection failed: php_network_getaddresses: getaddrinfo failed"

**Solution:**
1. Verify MySQL is running (green indicator in Laragon)
2. Check `config.php` has correct credentials
3. Verify database name matches `DB_NAME` in config.php
4. Try in phpMyAdmin first to test connection

### Cannot Access Website

**Problem:** "localhost refused to connect" or "404 Not Found"

**Solution:**
1. Verify files are in: `C:\Laragon\www\electropmart\`
2. Check Apache is running (green indicator)
3. Try accessing: `http://localhost/`
4. Clear browser cache
5. Try different browser

### phpMyAdmin Not Working

**Problem:** "Access denied for user 'root'@'localhost'"

**Solution:**
1. In Laragon, right-click > MySQL > Open MySQL Console
2. Login with: `mysql -u root`
3. Check database exists: `SHOW DATABASES;`
4. Verify user has privileges

### Ports Already in Use

**Problem:** "Port 80 is already in use"

**Solution:**
1. In Laragon, click Menu > Preferences
2. Under Apache > Port, change to 8080 (or higher)
3. Update `SITE_URL` in `config.php`:
   ```php
   define('SITE_URL', 'http://localhost:8080/electropmart/');
   ```
4. Restart Apache

### Files Not Updating

**Problem:** Changes to PHP files don't take effect

**Solution:**
1. Clear browser cache (Ctrl+Shift+Delete)
2. Restart Apache in Laragon
3. Wait a few seconds for files to reload
4. Try incognito/private browsing mode

---

## File Locations Reference

```
C:\Laragon\
├── www\electropmart\
│   ├── config.php                 ← Edit database settings here
│   ├── database.sql               ← Database schema
│   ├── index.php                  ← Homepage
│   ├── admin/
│   │   ├── dashboard.php          ← Admin dashboard
│   │   ├── products.php           ← Manage products
│   │   ├── orders.php             ← Manage orders
│   │   ├── categories.php         ← Manage categories
│   │   └── users.php              ← Manage users
│   ├── assets/
│   │   ├── css/style.css
│   │   └── js/script.js
│   └── api/                       ← API endpoints
│
├── bin\
│   ├── php\                       ← PHP executable
│   └── mysql\                     ← MySQL executable
│
├── data\
│   └── mysql\                     ← Database files
│
└── etc\
    ├── apache2\                   ← Apache config
    └── hosts                      ← Virtual hosts
```

---

## Performance Tips

### Database Optimization
1. Add indexes to frequently queried columns
2. Use pagination for large product lists
3. Cache query results when appropriate

### Image Optimization
1. Compress images before adding to store
2. Use appropriate file formats (JPG for photos, PNG for graphics)
3. Consider lazy loading for product images

### Server Optimization
1. Enable OPcache in PHP settings
2. Use browser caching headers
3. Minimize CSS and JavaScript files

---

## Development Features

### Error Reporting

Edit `config.php` to enable/disable error display:

```php
// Development (show all errors)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Production (hide errors from users)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

### Database Backup

1. In Laragon, right-click > MySQL > Open MySQL Console
2. Run:
   ```bash
   mysqldump -u root electropmart > backup.sql
   ```

3. Or use phpMyAdmin:
   - Select database
   - Click "Export"
   - Choose format and download

### Session Management

Sessions are stored in Laragon's PHP temp directory. Clear them:
1. Delete all cookies in browser
2. Or navigate to: `C:\Laragon\tmp\` and clear PHP session files

---

## Next Steps

### After Setup
1. ✅ Add test products and categories
2. ✅ Test checkout process
3. ✅ Test admin dashboard features
4. ✅ Create sample orders
5. ✅ Test all user workflows

### For Production
1. Switch to dedicated hosting (InfinityFree or other)
2. Use HTTPS/SSL certificate
3. Update database credentials
4. Enable security measures
5. Set up email notifications
6. Configure payment gateway

---

## Useful Resources

- **Laragon Official:** https://laragon.org
- **PHP Documentation:** https://www.php.net/manual/
- **MySQL Documentation:** https://dev.mysql.com/doc/
- **Apache Documentation:** https://httpd.apache.org/docs/

---

## Quick Start Commands

```bash
# Access MySQL console
mysql -u root

# Create database
CREATE DATABASE electropmart;

# Select database
USE electropmart;

# Show all tables
SHOW TABLES;

# Import SQL file
mysql -u root electropmart < database.sql

# Backup database
mysqldump -u root electropmart > backup.sql

# Check PHP version
php -v

# Start local server (alternative)
cd C:\Laragon\www\electropmart
php -S localhost:8000
```

---

## Support

If you encounter issues:

1. Check `config.php` settings match your setup
2. Verify all files are in `C:\Laragon\www\electropmart\`
3. Ensure MySQL database was properly imported
4. Check Laragon services are running
5. Review PHP error logs
6. Try restarting Laragon services

---

**Electropmart v1.0 | Laragon Development Ready**

Happy developing! 🚀
