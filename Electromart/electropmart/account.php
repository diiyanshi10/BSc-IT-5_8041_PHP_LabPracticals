<?php
require_once 'config.php';

if (!is_user_logged_in()) {
    redirect(SITE_URL . 'login.php');
}

$page_title = 'My Account';
include 'header.php';

$user_id = get_user_id();
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

$tab = $_GET['tab'] ?? 'orders';

// Get user orders
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");

// Get wishlist
$wishlist = $conn->query("
    SELECT p.id, p.name, p.price, p.discount_price, p.image_url, p.rating
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = $user_id
    ORDER BY w.created_at DESC
");
?>

<section class="section">
    <div class="container">
        <div class="account-container">
            <!-- Sidebar Navigation -->
            <aside class="account-sidebar">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <nav class="account-menu">
                    <a href="?tab=orders" class="menu-item <?php echo $tab === 'orders' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-bag"></i> My Orders
                    </a>
                    <a href="?tab=profile" class="menu-item <?php echo $tab === 'profile' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="?tab=address" class="menu-item <?php echo $tab === 'address' ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt"></i> Address
                    </a>
                    <a href="?tab=wishlist" class="menu-item <?php echo $tab === 'wishlist' ? 'active' : ''; ?>">
                        <i class="fas fa-heart"></i> Wishlist
                    </a>
                    <a href="logout.php" class="menu-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </aside>

            <!-- Content Area -->
            <div class="account-content">
                <!-- My Orders Tab -->
                <?php if ($tab === 'orders'): ?>
                    <h2>My Orders</h2>
                    <?php if ($orders->num_rows > 0): ?>
                        <div class="orders-list">
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                        <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                    </div>
                                    <div class="order-body">
                                        <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                                        <p><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                                        <p><strong>Payment:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                                    </div>
                                    <div class="order-footer">
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">View Details</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p>You haven't placed any orders yet.</p>
                        <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Start Shopping</a>
                    <?php endif; ?>

                <!-- Profile Tab -->
                <?php elseif ($tab === 'profile'): ?>
                    <h2>Edit Profile</h2>
                    <form method="POST" action="api/update-profile.php" class="form">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>

                        <h3 style="margin-top: 30px;">Change Password</h3>

                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Leave blank to keep current password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>

                <!-- Address Tab -->
                <?php elseif ($tab === 'address'): ?>
                    <h2>Delivery Address</h2>
                    <form method="POST" action="api/update-address.php" class="form">
                        <div class="form-group">
                            <label for="address">Street Address</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="state">State/Province</label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </form>

                <!-- Wishlist Tab -->
                <?php elseif ($tab === 'wishlist'): ?>
                    <h2>My Wishlist</h2>
                    <?php if ($wishlist->num_rows > 0): ?>
                        <div class="products-grid">
                            <?php while ($product = $wishlist->fetch_assoc()):
                                $discount = $product['discount_price'] ? round((($product['price'] - $product['discount_price']) / $product['price']) * 100) : 0;
                            ?>
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        <?php if ($discount > 0): ?>
                                            <span class="discount-badge">-<?php echo $discount; ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-info">
                                        <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                        <div class="price">
                                            <?php if ($product['discount_price']): ?>
                                                <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                                                <span class="sale-price">$<?php echo number_format($product['discount_price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="sale-price">$<?php echo number_format($product['price'], 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p>Your wishlist is empty.</p>
                        <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Continue Shopping</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
