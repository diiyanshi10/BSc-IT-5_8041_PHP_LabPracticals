<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Dashboard';
include '../header.php';

// Get statistics
$total_products = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_active = TRUE")->fetch_assoc()['count'];
$total_categories = $conn->query("SELECT COUNT(*) as count FROM categories WHERE is_active = TRUE")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'") ->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];

// Revenue calculations
$today_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'];
$month_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'")->fetch_assoc()['total'];

// Recent orders
$recent_orders = $conn->query("SELECT o.*, u.full_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");

// Order status breakdown
$pending_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];
$processing_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'processing' OR status = 'confirmed'")->fetch_assoc()['count'];
$shipped_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'shipped'")->fetch_assoc()['count'];
$delivered_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'delivered'")->fetch_assoc()['count'];

// Low stock products
$low_stock = $conn->query("SELECT id, name, stock FROM products WHERE stock <= 10 AND stock > 0 ORDER BY stock ASC LIMIT 5");

// Top products by revenue
$top_products = $conn->query("SELECT p.id, p.name, SUM(oi.subtotal) as revenue, SUM(oi.quantity) as sold 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              GROUP BY p.id, p.name 
                              ORDER BY revenue DESC 
                              LIMIT 5");
?>

<section class="section">
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Key Metrics -->
        <div class="dashboard-metrics">
            <div class="metric-card primary">
                <div class="metric-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="metric-content">
                    <div class="metric-label">Today's Revenue</div>
                    <div class="metric-value">$<?php echo number_format($today_revenue ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="metric-card secondary">
                <div class="metric-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="metric-content">
                    <div class="metric-label">Month's Revenue</div>
                    <div class="metric-value">$<?php echo number_format($month_revenue ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="metric-card success">
                <div class="metric-icon"><i class="fas fa-chart-bar"></i></div>
                <div class="metric-content">
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value">$<?php echo number_format($total_revenue ?? 0, 2); ?></div>
                </div>
            </div>

            <div class="metric-card warning">
                <div class="metric-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="metric-content">
                    <div class="metric-label">Low Stock Items</div>
                    <div class="metric-value"><?php echo $low_stock->num_rows; ?></div>
                </div>
            </div>
        </div>

        <!-- Overview Statistics -->
        <div class="dashboard-grid">
            <div class="dashboard-section">
                <h2>Overview</h2>
                <div class="overview-stats">
                    <div class="stat-item">
                        <span class="stat-icon products"><i class="fas fa-box"></i></span>
                        <span class="stat-label">Products</span>
                        <span class="stat-number"><?php echo $total_products; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon categories"><i class="fas fa-list"></i></span>
                        <span class="stat-label">Categories</span>
                        <span class="stat-number"><?php echo $total_categories; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon users"><i class="fas fa-users"></i></span>
                        <span class="stat-label">Customers</span>
                        <span class="stat-number"><?php echo $total_users; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon orders"><i class="fas fa-shopping-bag"></i></span>
                        <span class="stat-label">Total Orders</span>
                        <span class="stat-number"><?php echo $total_orders; ?></span>
                    </div>
                </div>
            </div>

            <div class="dashboard-section">
                <h2>Order Status</h2>
                <div class="order-status">
                    <div class="status-item pending">
                        <span class="status-circle"></span>
                        <span class="status-text">Pending</span>
                        <span class="status-count"><?php echo $pending_orders; ?></span>
                    </div>
                    <div class="status-item processing">
                        <span class="status-circle"></span>
                        <span class="status-text">Processing</span>
                        <span class="status-count"><?php echo $processing_orders; ?></span>
                    </div>
                    <div class="status-item shipped">
                        <span class="status-circle"></span>
                        <span class="status-text">Shipped</span>
                        <span class="status-count"><?php echo $shipped_orders; ?></span>
                    </div>
                    <div class="status-item delivered">
                        <span class="status-circle"></span>
                        <span class="status-text">Delivered</span>
                        <span class="status-count"><?php echo $delivered_orders; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Top Products -->
        <div class="dashboard-grid">
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Recent Orders</h2>
                    <a href="orders.php" class="link">View All</a>
                </div>
                <div class="recent-orders">
                    <?php if ($recent_orders->num_rows > 0): ?>
                        <?php while ($order = $recent_orders->fetch_assoc()): ?>
                            <div class="order-row">
                                <div class="order-info">
                                    <div class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></div>
                                    <div class="order-customer"><?php echo htmlspecialchars($order['full_name']); ?></div>
                                </div>
                                <div class="order-amount">$<?php echo number_format($order['total_amount'], 2); ?></div>
                                <div class="order-status">
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="padding: 20px; text-align: center; color: var(--text-light);">No orders yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Top Products</h2>
                    <a href="products.php" class="link">View All</a>
                </div>
                <div class="top-products">
                    <?php if ($top_products->num_rows > 0): ?>
                        <?php while ($product = $top_products->fetch_assoc()): ?>
                            <div class="product-row">
                                <div class="product-info">
                                    <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="product-sold"><?php echo $product['sold']; ?> sold</div>
                                </div>
                                <div class="product-revenue">$<?php echo number_format($product['revenue'], 2); ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="padding: 20px; text-align: center; color: var(--text-light);">No sales yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <?php if ($low_stock->num_rows > 0): ?>
            <div class="dashboard-section alert-section">
                <div class="section-header">
                    <h2><i class="fas fa-exclamation-circle"></i> Low Stock Alert</h2>
                </div>
                <div class="low-stock-items">
                    <?php while ($item = $low_stock->fetch_assoc()): ?>
                        <div class="stock-item">
                            <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="stock-level">
                                <span class="stock-bar">
                                    <span class="stock-fill" style="width: <?php echo ($item['stock'] / 10) * 100; ?>%"></span>
                                </span>
                                <?php echo $item['stock']; ?> units
                            </span>
                            <a href="products.php?action=edit&id=<?php echo $item['id']; ?>" class="link">Restock</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="dashboard-section quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="products.php?action=add" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Add Product</span>
                </a>
                <a href="categories.php?action=add" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Add Category</span>
                </a>
                <a href="orders.php" class="action-btn">
                    <i class="fas fa-box-open"></i>
                    <span>View Orders</span>
                </a>
                <a href="users.php" class="action-btn">
                    <i class="fas fa-users"></i>
                    <span>Manage Users</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.dashboard-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.metric-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    gap: 15px;
    box-shadow: var(--shadow-lg);
}

.metric-card.primary {
    background: linear-gradient(135deg, var(--primary-color), #FF7A00);
}

.metric-card.secondary {
    background: linear-gradient(135deg, var(--secondary-color), #0E4A9C);
}

.metric-card.success {
    background: linear-gradient(135deg, var(--success-color), #1E8449);
}

.metric-card.warning {
    background: linear-gradient(135deg, var(--warning-color), #D68910);
}

.metric-icon {
    font-size: 32px;
    opacity: 0.8;
}

.metric-content {
    flex: 1;
}

.metric-label {
    font-size: 12px;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.metric-value {
    font-size: 24px;
    font-weight: bold;
    margin-top: 5px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.dashboard-section {
    background-color: var(--bg-white);
    padding: 20px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.dashboard-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 18px;
    color: var(--text-dark);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    margin: 0;
}

.link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 12px;
    font-weight: bold;
}

.link:hover {
    text-decoration: underline;
}

.overview-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    background-color: var(--bg-light);
    border-radius: 8px;
    text-align: center;
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
}

.stat-icon.products { background-color: #3498DB; }
.stat-icon.categories { background-color: #E74C3C; }
.stat-icon.users { background-color: #2ECC71; }
.stat-icon.orders { background-color: #F39C12; }

.stat-label {
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 5px;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: var(--text-dark);
}

.order-status {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background-color: var(--bg-light);
    border-radius: 6px;
}

.status-circle {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-item.pending .status-circle { background-color: #F39C12; }
.status-item.processing .status-circle { background-color: #9B59B6; }
.status-item.shipped .status-circle { background-color: #16A085; }
.status-item.delivered .status-circle { background-color: #27AE60; }

.status-text {
    flex: 1;
    font-weight: 500;
}

.status-count {
    background-color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 12px;
}

.recent-orders,
.top-products {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-row,
.product-row {
    display: flex;
    align-items: center;
    padding: 12px;
    background-color: var(--bg-light);
    border-radius: 6px;
    gap: 12px;
}

.order-info {
    flex: 1;
}

.order-number {
    font-weight: bold;
    font-size: 12px;
    color: var(--primary-color);
}

.order-customer {
    font-size: 12px;
    color: var(--text-light);
}

.order-amount {
    font-weight: bold;
    min-width: 80px;
    text-align: right;
}

.order-status {
    min-width: 100px;
    text-align: right;
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 4px;
}

.product-sold {
    font-size: 11px;
    color: var(--text-light);
}

.product-revenue {
    font-weight: bold;
    min-width: 80px;
    text-align: right;
}

.alert-section {
    border-left: 4px solid var(--warning-color);
}

.low-stock-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stock-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    background-color: var(--bg-light);
    border-radius: 6px;
}

.stock-level {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
}

.stock-bar {
    flex: 1;
    height: 6px;
    background-color: #ddd;
    border-radius: 3px;
    overflow: hidden;
    min-width: 100px;
}

.stock-fill {
    height: 100%;
    background-color: var(--warning-color);
    display: block;
    transition: width 0.3s;
}

.quick-actions {
    margin-top: 20px;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 20px;
    background: linear-gradient(135deg, var(--primary-color), #FF7A00);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.action-btn i {
    font-size: 24px;
}

.action-btn span {
    font-size: 12px;
    font-weight: bold;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    color: white;
}

.status-pending { background-color: #F39C12; }
.status-confirmed { background-color: #3498DB; }
.status-processing { background-color: #9B59B6; }
.status-shipped { background-color: #16A085; }
.status-delivered { background-color: #27AE60; }
.status-cancelled { background-color: #E74C3C; }

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .overview-stats {
        grid-template-columns: 1fr 1fr;
    }

    .action-buttons {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashboard-metrics {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../footer.php'; ?>
