# Electropmart Admin Dashboard - Complete Guide

Professional admin panel for managing your e-commerce store with comprehensive product, order, category, user, and coupon management.

---

## 📊 Dashboard Features

### 1. Dashboard Home (`admin/dashboard.php`)

**Real-time Analytics:**
- Today's revenue
- Monthly revenue
- Total revenue (completed orders)
- Low stock alerts
- Overview statistics (products, categories, customers, orders)
- Order status breakdown
- Recent orders list
- Top selling products
- Quick action buttons

**Visual Indicators:**
- Gradient metric cards with live data
- Color-coded order status indicators
- Low stock warning system
- Recent activity feed

### 2. Product Management (`admin/products.php`)

**Features:**
- ✅ Add new products
- ✅ Edit existing products
- ✅ Delete products
- ✅ Manage product images
- ✅ Set prices and discounts
- ✅ Track inventory/stock levels
- ✅ Mark products as featured
- ✅ Product descriptions
- ✅ Category assignment
- ✅ Image URL management
- ✅ Bulk operations support

**Product Form Fields:**
- Product Name (required)
- Category (required)
- Price (required)
- Discount Price (optional)
- Stock Quantity (required)
- Description
- Image URL
- Featured status checkbox

**Table View Columns:**
- Thumbnail image
- Product name
- Category
- Original price
- Discount price
- Stock level with color badges
- Featured status
- Edit/Delete actions

### 3. Order Management (`admin/orders.php`)

**Features:**
- ✅ View all orders
- ✅ Filter by order status
- ✅ View detailed order information
- ✅ Update order status
- ✅ Update payment status
- ✅ Track customer information
- ✅ View order items
- ✅ Calculate order totals
- ✅ View shipping address

**Order Status Workflow:**
1. **Pending** → New order received, awaiting confirmation
2. **Confirmed** → Customer confirmed order
3. **Processing** → Order being prepared
4. **Shipped** → Order on its way
5. **Delivered** → Order received by customer
6. **Cancelled** → Order cancelled

**Payment Status Options:**
- Pending
- Completed
- Failed
- Refunded

**Order Details Include:**
- Customer name and email
- Phone number
- Shipping address
- Order items with quantities
- Price breakdown (subtotal, shipping, tax, total)
- Payment method
- Order timestamps

### 4. Category Management (`admin/categories.php`)

**Features:**
- ✅ Add new categories
- ✅ Edit categories
- ✅ Delete categories
- ✅ Upload category images
- ✅ Toggle active/inactive status
- ✅ View product count per category
- ✅ Category descriptions

**Category Information:**
- Category name
- Description
- Image URL
- Active/Inactive status
- Product count for each category

### 5. User Management (`admin/users.php`)

**Features:**
- ✅ View all users
- ✅ Edit user information
- ✅ Delete users
- ✅ Change user roles (customer/admin)
- ✅ Activate/deactivate accounts
- ✅ View registration dates
- ✅ Manage customer profiles

**User Roles:**
- **Customer**: Regular store customer
- **Admin**: Full store management access

**User Information:**
- Full name
- Email address
- Username (read-only)
- Phone number
- Address
- City/State/Country
- Role assignment
- Active status
- Registration date
- Last update date

### 6. Coupon Management (`admin/coupons.php`)

**Features:**
- ✅ Create discount coupons
- ✅ Set discount type (percentage or fixed amount)
- ✅ Set discount value
- ✅ Limit coupon uses
- ✅ Set expiry dates
- ✅ Track coupon usage
- ✅ Enable/disable coupons
- ✅ Edit and delete coupons

**Coupon Types:**
- **Percentage**: Discount as % of total (e.g., 20%)
- **Fixed Amount**: Discount as $ amount (e.g., $5)

**Coupon Information:**
- Unique coupon code
- Discount type and value
- Maximum uses (0 = unlimited)
- Current usage count
- Expiry date
- Active/inactive status

---

## 🎨 Admin UI Components

### Navigation
- **Left Sidebar Menu**: Quick access to all admin sections
- **Top Bar**: Logo, search, logout
- **Breadcrumbs**: Current location indicator

### Tables
- **Professional Design**: Clean, modern table layouts
- **Status Badges**: Color-coded status indicators
- **Thumbnail Images**: Product/category image previews
- **Action Buttons**: Edit, Delete, View options
- **Sorting & Filtering**: Quick access filters

### Forms
- **Input Validation**: Required field indicators
- **Clear Labels**: Descriptive field labels
- **Help Text**: Guidance for complex fields
- **Image Previews**: Live image preview
- **Save/Cancel Buttons**: Form action controls

### Alerts
- **Success Messages**: Green alert for completed actions
- **Error Messages**: Red alert for validation errors
- **Warning Badges**: Yellow indicators for alerts

---

## 🔐 Admin Authentication

### Access Control
- Only admins can access `/admin/` routes
- Automatic redirect for non-admin users
- Session-based authentication
- Logout functionality

### Admin Account Creation

**Via Registration + Database Update:**
1. Register new account at `/register.php`
2. Login to phpMyAdmin
3. Go to `electropmart > users` table
4. Find your account
5. Change `role` column from 'customer' to 'admin'
6. Save and logout/login

**Or via MySQL:**
```sql
UPDATE users SET role='admin' WHERE email='admin@example.com';
```

---

## 📈 Dashboard Metrics

### Key Performance Indicators (KPIs)

**Revenue Metrics:**
- Today's Revenue: Real-time sales today
- Monthly Revenue: Current month's sales
- Total Revenue: All completed orders combined

**Product Metrics:**
- Total Products: Active product count
- Total Categories: Category count
- Low Stock Items: Products with stock ≤ 10

**Order Metrics:**
- Total Orders: All orders count
- Pending Orders: Awaiting confirmation
- Processing Orders: Being prepared
- Shipped Orders: In transit
- Delivered Orders: Completed successfully

**Customer Metrics:**
- Total Customers: Registered users (customer role)
- Customer Growth: Track over time

---

## 🛠️ Admin Workflows

### Adding a Product

1. Go to Admin > Manage Products
2. Click "+ Add New Product"
3. Fill in product details:
   - Name
   - Category (select from dropdown)
   - Price (required)
   - Discount Price (optional, for sales)
   - Stock Quantity
   - Description
   - Image URL (link to product image)
   - Check "Featured Product" if applicable
4. Click "Add Product"
5. Product appears in catalog immediately

### Processing an Order

1. Go to Admin > View Orders
2. Click on an order to view details
3. Review order information:
   - Customer details
   - Items ordered
   - Total amount
   - Current status
4. Update status:
   - Change from "Pending" → "Confirmed"
   - Change to "Processing" when preparing
   - Change to "Shipped" when sent out
   - Change to "Delivered" when received
5. Update payment status if needed
6. Click "Update Status"
7. Customer receives status notifications (when enabled)

### Creating a Discount Coupon

1. Go to Admin > Manage Coupons
2. Click "+ Add Coupon"
3. Enter coupon details:
   - Code (e.g., "SUMMER20")
   - Discount Type (% or $)
   - Discount Value
   - Max Uses (optional)
   - Expiry Date (optional)
   - Check "Active"
4. Click "Add Coupon"
5. Customers can apply code at checkout

### Promoting a Customer to Admin

1. Go to Admin > Manage Users
2. Click "Edit" on a customer
3. Change "Role" from "Customer" to "Admin"
4. Click "Update User"
5. User gains admin access on next login

---

## 📱 Responsive Design

### Desktop (1200px+)
- Full sidebar visible
- Multi-column tables
- Side-by-side forms and previews
- All features fully visible

### Tablet (768px - 1199px)
- Collapsible sidebar
- Adapted table layouts
- Optimized forms
- Touch-friendly buttons

### Mobile (< 768px)
- Hamburger menu
- Single-column layouts
- Stacked forms
- Touch-optimized UI

---

## 🔒 Security Features

### Input Protection
- SQL injection prevention (parameterized queries)
- XSS protection (HTML escaping)
- Input validation and sanitization
- CSRF token validation (when enabled)

### Access Control
- Role-based access (admin-only)
- Session-based authentication
- Automatic logout
- Secure password hashing

### Data Safety
- Database transactions
- Backup recommendations
- Audit logs (optional)
- Soft deletes (optional upgrade)

---

## ⚙️ Technical Specifications

### Technologies
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache or Nginx

### Performance
- Optimized database queries
- Pagination support for large datasets
- Image optimization recommendations
- Caching strategies

### Compatibility
- Chrome/Firefox/Safari/Edge
- Windows/Mac/Linux
- Desktop/Tablet/Mobile
- 1024x768+ screen resolution minimum

---

## 📊 Database Schema

### Related Tables

**Products Table:**
```sql
- id: Product ID
- name: Product name
- category_id: Category reference
- price: Original price
- discount_price: Sale price
- stock: Inventory quantity
- image_url: Product image URL
- is_featured: Featured flag
```

**Orders Table:**
```sql
- id: Order ID
- user_id: Customer reference
- total_amount: Order total
- status: Current status
- payment_status: Payment state
- created_at: Order date
```

**Categories Table:**
```sql
- id: Category ID
- name: Category name
- image_url: Category image
- is_active: Active flag
```

**Users Table:**
```sql
- id: User ID
- email: Email address
- role: user/admin
- is_active: Account status
```

**Coupons Table:**
```sql
- id: Coupon ID
- code: Coupon code
- discount_type: percentage/fixed
- discount_value: Amount/percentage
- expiry_date: Expiration date
- is_active: Active flag
```

---

## 🚀 Advanced Features

### Upcoming Enhancements
- Advanced analytics charts
- Customer behavior tracking
- Automated email notifications
- Inventory alerts
- Multi-language support
- Custom report generation
- Bulk import/export
- Advanced user permissions
- API access tokens

---

## 📞 Support & Troubleshooting

### Common Issues

**Cannot access admin panel:**
- Verify you're logged in
- Check user role is 'admin' in database
- Clear browser cache
- Try incognito mode

**Products not showing:**
- Verify products are marked as active
- Check category exists and is active
- Verify product has image URL
- Check database connection

**Orders not displaying:**
- Check orders exist in database
- Verify user accounts for orders
- Check date filters if applied
- Try clearing browser cache

**Images not loading:**
- Verify image URLs are accessible
- Check URL is complete (http/https)
- Ensure CORS headers allow access
- Test image URL in browser directly

---

## 📚 File Structure

```
admin/
├── index.php              ← Redirects to dashboard
├── dashboard.php          ← Main analytics dashboard
├── products.php           ← Product CRUD operations
├── orders.php             ← Order management & tracking
├── categories.php         ← Category management
├── users.php              ← User management
├── coupons.php            ← Coupon management
└── settings.php           ← Store settings (future)
```

---

## ✅ Checklist for First Time Use

- [ ] Create admin account via register.php
- [ ] Promote account to admin in database
- [ ] Login to admin dashboard
- [ ] Add product categories
- [ ] Add sample products
- [ ] Test product visibility on frontend
- [ ] Create test coupon
- [ ] Place test order
- [ ] Process test order through dashboard
- [ ] Verify all workflows work correctly

---

## 🎯 Best Practices

### Product Management
✅ Use clear, descriptive product names
✅ Include detailed descriptions
✅ Add high-quality images
✅ Update stock regularly
✅ Use categories consistently

### Order Management
✅ Update order status promptly
✅ Keep customer informed
✅ Verify shipping addresses
✅ Process payments accurately
✅ Document special requests

### User Management
✅ Verify user information
✅ Only promote trusted users to admin
✅ Deactivate unused accounts
✅ Review user activity regularly
✅ Maintain backup admin accounts

### Coupon Management
✅ Use descriptive coupon codes
✅ Set reasonable expiry dates
✅ Limit usage appropriately
✅ Track promotion effectiveness
✅ Monitor coupon abuse

---

**Electropmart Admin Dashboard v1.0**

*Professional e-commerce management in your hands*

