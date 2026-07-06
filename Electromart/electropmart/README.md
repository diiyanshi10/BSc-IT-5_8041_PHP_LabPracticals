# Electropmart - Professional E-Commerce Platform

A fully functional PHP-based e-commerce platform designed for electronic stores, featuring product management, shopping cart, secure checkout, and admin dashboard.

## Features

### 🛍️ Customer Features
- **Product Catalog** - Browse electronics by category with advanced filtering
- **Product Search** - Real-time search functionality across all products
- **Product Details** - Comprehensive product pages with images, ratings, and reviews
- **Shopping Cart** - Add/remove items, adjust quantities, view totals
- **Wishlist** - Save favorite products for later purchase
- **User Accounts** - Register, login, manage profile and orders
- **Checkout** - Secure multi-step checkout process
- **Order Tracking** - View order history and status
- **Ratings & Reviews** - Leave and view product reviews
- **Responsive Design** - Works perfectly on desktop, tablet, and mobile

### 👨‍💼 Admin Features
- **Dashboard** - Overview of sales, products, users, and revenue
- **Product Management** - Add, edit, delete products with images
- **Category Management** - Organize products into categories
- **Order Management** - Track and manage customer orders
- **User Management** - View and manage customer accounts
- **Coupon System** - Create and manage discount codes
- **Analytics** - Sales and revenue tracking

### 🔐 Security Features
- Password hashing with bcrypt
- SQL injection protection
- Session management
- Input validation and sanitization
- HTTPS ready for production

## System Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Web Server** Apache or Nginx
- **Storage** 100MB minimum
- **Bandwidth** 1GB+ recommended

## Installation

### Quick Start (Local Development)

1. **Clone/Extract Files**
   ```bash
   cd /var/www/html
   git clone <repository> electropmart
   cd electropmart
   ```

2. **Create Database**
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Configure Database**
   - Edit `config.php`
   - Update database credentials

4. **Set Permissions**
   ```bash
   chmod 755 -R electropmart
   chmod 644 config.php
   ```

5. **Access Application**
   - Browser: `http://localhost/electropmart`
   - Create account and login

### Deployment to InfinityFree

Follow the detailed guide in `DEPLOYMENT_GUIDE.md` for step-by-step instructions to deploy on InfinityFree hosting.

## Project Structure

```
electropmart/
├── index.php                 # Homepage
├── product.php              # Product detail page
├── cart.php                 # Shopping cart
├── checkout.php             # Checkout process
├── login.php                # User login
├── register.php             # User registration
├── account.php              # User account dashboard
├── order-confirmation.php   # Order confirmation
├── order-details.php        # Order details page
├── category.php             # Category browsing
├── search.php               # Search results
├── wishlist.php             # User wishlist
├── deals.php                # Special deals/discounts
├── logout.php               # Logout functionality
├── config.php               # Database configuration
├── header.php               # Header template
├── footer.php               # Footer template
├── database.sql             # Database schema
│
├── admin/
│   ├── index.php            # Admin dashboard
│   ├── products.php         # Product management
│   ├── categories.php       # Category management
│   ├── orders.php           # Order management
│   ├── users.php            # User management
│   ├── coupons.php          # Coupon management
│   └── settings.php         # Store settings
│
├── api/
│   ├── add-to-wishlist.php  # Wishlist API
│   └── remove-from-wishlist.php  # Wishlist removal
│
├── assets/
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   └── js/
│       └── script.js        # Main JavaScript
│
├── DEPLOYMENT_GUIDE.md      # InfinityFree deployment guide
├── README.md                # This file
└── .htaccess               # Apache configuration
```

## Database Schema

The application includes the following main tables:

- `users` - Customer and admin accounts
- `categories` - Product categories
- `products` - Product listings
- `product_images` - Product images gallery
- `cart` - Shopping cart items
- `orders` - Customer orders
- `order_items` - Items in each order
- `reviews` - Product reviews and ratings
- `wishlist` - User wishlist items
- `coupons` - Discount codes

## Configuration

Edit `config.php` to configure:

```php
define('DB_HOST', 'localhost');      // Database host
define('DB_USER', 'root');           // Database user
define('DB_PASS', '');               // Database password
define('DB_NAME', 'electropmart');   // Database name
define('SITE_URL', 'http://localhost/electropmart/');  // Site URL
define('SITE_NAME', 'Electropmart'); // Store name
define('SITE_EMAIL', 'admin@electropmart.com');  // Admin email
```

## User Roles

### Customer
- Browse products
- Add to cart
- Checkout and pay
- View order history
- Leave reviews
- Manage wishlist
- Update profile

### Admin
- Full dashboard access
- Product management
- Order management
- User management
- Store settings
- Analytics and reports

## Creating an Admin Account

1. Register a new account at `/register.php`
2. Via phpMyAdmin:
   - Open the `users` table
   - Find your account
   - Change `role` from 'customer' to 'admin'
   - Save

3. Logout and login to access admin panel

## Payment Integration

The checkout includes payment method selection. To enable actual payment processing:

1. **Credit Card** - Integrate with Stripe, PayPal, or similar
2. **PayPal** - Use PayPal SDK
3. **Bank Transfer** - Manual processing
4. **Other Methods** - Based on your requirements

See `checkout.php` for integration points.

## Email Configuration

To send order confirmation emails:

1. Edit `checkout.php`
2. Add after order creation:
   ```php
   mail($customer_email, 'Order Confirmation', $message, $headers);
   ```

## Security Checklist

- [ ] Update all default passwords
- [ ] Enable HTTPS/SSL certificate
- [ ] Set strong database credentials
- [ ] Restrict file permissions appropriately
- [ ] Regular database backups
- [ ] Keep PHP and MySQL updated
- [ ] Monitor for suspicious activity
- [ ] Validate all user inputs
- [ ] Use prepared statements for queries

## Troubleshooting

### Database Connection Error
- Check credentials in `config.php`
- Verify MySQL is running
- Ensure database exists and is accessible

### Blank Pages
- Check error logs
- Enable error reporting in `config.php`
- Verify all files are uploaded
- Check PHP version compatibility

### Session Issues
- Verify PHP sessions are enabled
- Check server has writable temp directory
- Clear browser cookies

### Upload Problems
- Check directory permissions
- Verify file size limits
- Use ASCII mode for text files in FTP

## Performance Optimization

1. **Database**
   - Index frequently queried columns
   - Use pagination for large result sets
   - Cache query results when appropriate

2. **Images**
   - Optimize and compress images
   - Use appropriate file formats
   - Implement lazy loading

3. **Code**
   - Minify CSS and JavaScript
   - Reduce HTTP requests
   - Use browser caching

## Scaling

For high-traffic stores:

1. Upgrade hosting to dedicated/VPS
2. Implement caching (Redis, Memcached)
3. Optimize database queries
4. Use CDN for static assets
5. Consider load balancing

## Support

- Review `DEPLOYMENT_GUIDE.md` for hosting questions
- Check PHP documentation: https://www.php.net/manual/
- MySQL help: https://dev.mysql.com/doc/
- InfinityFree support: https://www.infinityfree.net/support/

## License

This project is provided as-is for educational and commercial use.

## Version

**Electropmart v1.0**
- Initial release
- Full e-commerce functionality
- Admin dashboard
- Multi-device responsive design

## Credits

Built with PHP, MySQL, and modern web technologies.

---

**Happy Selling! 🎉**
