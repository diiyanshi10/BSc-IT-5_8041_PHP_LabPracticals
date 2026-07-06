# Electropmart - InfinityFree Deployment Guide

Complete guide to deploy Electropmart e-commerce platform on InfinityFree hosting.

## Prerequisites

1. **InfinityFree Account** - Free hosting account at [infinityfree.net](https://www.infinityfree.net)
2. **FTP Client** - FileZilla or similar (for uploading files)
3. **Database Access** - MySQL database provided by InfinityFree
4. **Domain/Subdomain** - Your website URL

## Step 1: Create InfinityFree Account & Setup Hosting

1. Visit [infinityfree.net](https://www.infinityfree.net)
2. Click "Sign Up" and create a free account
3. Create a new website:
   - Choose a free domain or use your own
   - Select "PHP" as server type
   - Accept terms and create

4. **Note down your details:**
   - FTP Host: `ftp.yourdomain.infinityfree.com`
   - FTP Username: Your username
   - FTP Password: Your password
   - MySQL Host: `sql.infinityfree.com`
   - MySQL Database Name: `yourusername_dbname`
   - MySQL Username: `yourusername_dbuser`
   - MySQL Password: (created during setup)

## Step 2: Setup Database

1. **Login to InfinityFree Account Panel**
   - Go to your Control Panel
   - Click "MySQL Databases"

2. **Create Database:**
   - Database Name: `yourusername_electropmart`
   - Click "Create"

3. **Create Database User:**
   - Username: `yourusername_admin`
   - Password: Create a strong password
   - Click "Create User"

4. **Add User to Database:**
   - Select your database
   - Select the user
   - Select "All Privileges"
   - Click "Add User to Database"

5. **Import Database Schema:**
   - Go to "MySQL Databases"
   - Click "phpMyAdmin" next to your database
   - Click "Import"
   - Upload the `database.sql` file from this project
   - Click "Go" to execute

## Step 3: Upload Files via FTP

1. **Connect via FTP:**
   - Open FileZilla
   - Host: `ftp.yourdomain.infinityfree.com`
   - Username: Your FTP username
   - Password: Your FTP password
   - Port: 21
   - Click "Quickconnect"

2. **Navigate to public_html directory:**
   - Double-click `public_html` folder on the remote site

3. **Upload Electropmart Files:**
   - Drag and drop all files from the `electropmart` folder
   - Ensure all directories are uploaded:
     - `/assets/`
     - `/admin/`
     - `/api/`
   - Upload all `.php` files

## Step 4: Configure the Application

1. **Edit config.php:**
   - Open FileZilla and navigate to the uploaded files
   - Right-click `config.php`
   - Click "View/Edit"
   - Update the following with your InfinityFree credentials:

```php
define('DB_HOST', 'sql.infinityfree.com');
define('DB_USER', 'yourusername_admin');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'yourusername_electropmart');
define('SITE_URL', 'https://yourdomain.com/electropmart/');
```

2. **Save and upload the updated config.php**

## Step 5: Verify Installation

1. **Access your website:**
   - Go to `https://yourdomain.com/electropmart/`
   - You should see the Electropmart homepage

2. **Test database connection:**
   - If you see a connection error, verify your credentials in `config.php`

3. **Create test data:**
   - You can manually add categories and products via the admin panel
   - Or modify `database.sql` to include sample data

## Step 6: Create Admin Account

1. Access the registration page: `https://yourdomain.com/electropmart/register.php`
2. Create an admin account with your details
3. Contact server admin to promote account to admin (or manually update via phpMyAdmin)
4. In phpMyAdmin, go to `users` table
5. Find your admin user and change `role` from 'customer' to 'admin'
6. Save and logout/login

## Step 7: Access Admin Dashboard

1. Login with your admin account
2. Click "Admin Panel" in the navigation
3. Start managing products, categories, and orders

## Important Security Notes

1. **Update Credentials:**
   - Change default passwords immediately
   - Use strong, unique passwords
   - Never commit credentials to version control

2. **File Permissions:**
   - Ensure `config.php` is not publicly readable
   - InfinityFree handles this, but verify if possible

3. **HTTPS:**
   - InfinityFree provides free SSL certificates
   - Enable SSL in your account settings
   - Update `SITE_URL` in config.php to use `https://`

4. **Regular Backups:**
   - Backup your database regularly
   - Download files periodically

## Troubleshooting

### Cannot Connect to Database
- Verify credentials in `config.php`
- Ensure database user has permissions
- Check MySQL host is `sql.infinityfree.com`

### 404 Errors on Pages
- Verify all files uploaded to correct directory
- Check file permissions
- Ensure `.htaccess` is not blocking access

### Images Not Showing
- Verify image URLs in database are correct
- Check image file paths in assets folder
- Ensure all image files are uploaded

### Login Not Working
- Clear browser cookies
- Verify database connection
- Check user credentials in database via phpMyAdmin

### File Upload Issues
- Check file size limits (InfinityFree has limits)
- Verify directory permissions
- Use ASCII mode for text files in FTP

## Email Configuration (Optional)

To enable order confirmation emails:

1. Modify `checkout.php` to use `mail()` function
2. InfinityFree provides mail() support
3. Add email sending after order creation:

```php
mail($email, 'Order Confirmation', $message);
```

## Performance Optimization

1. **Enable Caching:**
   - Consider using PHP file caching for product listings
   - Reduce database queries where possible

2. **Optimize Images:**
   - Compress images before upload
   - Use appropriate file formats (WebP, JPEG, PNG)

3. **Minimize Code:**
   - Minify CSS and JavaScript
   - Combine files where possible

## SSL Certificate

1. Go to InfinityFree Control Panel
2. Click "SSL/TLS Certificates"
3. Select your domain
4. Click "Auto-renew"
5. Wait for activation (usually instant)
6. Update `SITE_URL` in config.php to use `https://`

## Adding a Custom Domain

1. Purchase domain from any registrar
2. In InfinityFree Control Panel, click "Addon Domains"
3. Enter your domain name
4. Point your domain nameservers to InfinityFree
5. Nameservers: (provided in InfinityFree account)

## Support & Resources

- **InfinityFree Support:** https://www.infinityfree.net/support/
- **PHP Documentation:** https://www.php.net/manual/
- **MySQL Documentation:** https://dev.mysql.com/doc/

## Next Steps

1. Add sample products and categories
2. Configure payment gateway (optional)
3. Set up email notifications
4. Customize branding and design
5. Launch and promote your store

---

**Electropmart v1.0 | Deployed on InfinityFree**
