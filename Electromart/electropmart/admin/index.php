<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Admin Dashboard';
include '../header.php';
?>

<section class="section">
    <div class="container">
        <h1>Admin Dashboard</h1>

        <div class="admin-stats">
            <?php
            $total_products = $conn->query("SELECT COUNT(*) as count FROM products WHERE is_active = TRUE")->fetch_assoc();
            $total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc();
            $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc();
            $total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'")->fetch_assoc();
            ?>

            <div class="stat-card">
                <i class="fas fa-box"></i>
                <h3><?php echo $total_products['count']; ?></h3>
                <p>Total Products</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-shopping-bag"></i>
                <h3><?php echo $total_orders['count']; ?></h3>
                <p>Total Orders</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $total_users['count']; ?></h3>
                <p>Total Users</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-dollar-sign"></i>
                <h3>$<?php echo number_format($total_revenue['total'] ?? 0, 2); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <div class="admin-menu">
            <a href="products.php" class="admin-link">
                <i class="fas fa-boxes"></i>
                <h3>Manage Products</h3>
                <p>Add, edit, or delete products</p>
            </a>

            <a href="categories.php" class="admin-link">
                <i class="fas fa-list"></i>
                <h3>Manage Categories</h3>
                <p>Organize product categories</p>
            </a>

            <a href="orders.php" class="admin-link">
                <i class="fas fa-receipt"></i>
                <h3>View Orders</h3>
                <p>Track and manage orders</p>
            </a>

            <a href="users.php" class="admin-link">
                <i class="fas fa-user-tie"></i>
                <h3>Manage Users</h3>
                <p>View and manage customers</p>
            </a>

            <a href="coupons.php" class="admin-link">
                <i class="fas fa-ticket-alt"></i>
                <h3>Manage Coupons</h3>
                <p>Create discount codes</p>
            </a>

            <a href="settings.php" class="admin-link">
                <i class="fas fa-cogs"></i>
                <h3>Settings</h3>
                <p>Configure store settings</p>
            </a>
        </div>
    </div>
</section>

<style>
.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
    box-shadow: var(--shadow-lg);
}

.stat-card i {
    font-size: 32px;
    margin-bottom: 15px;
}

.stat-card h3 {
    font-size: 28px;
    margin: 10px 0;
}

.stat-card p {
    font-size: 14px;
    opacity: 0.9;
}

.admin-menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.admin-link {
    background-color: var(--bg-white);
    padding: 30px;
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-dark);
    box-shadow: var(--shadow);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.admin-link:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.admin-link i {
    font-size: 32px;
    color: var(--primary-color);
}

.admin-link h3 {
    margin: 0;
    font-size: 18px;
}

.admin-link p {
    margin: 0;
    color: var(--text-light);
    font-size: 14px;
}
</style>

<?php include '../footer.php'; ?>
