<?php
require_once 'config.php';

if (!is_user_logged_in()) {
    redirect(SITE_URL . 'login.php');
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = get_user_id();

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id")->fetch_assoc();

if (!$order) {
    redirect(SITE_URL . 'account.php');
}

$page_title = 'Order Details';
include 'header.php';

$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<section class="section">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Home</a> / 
            <a href="account.php">My Account</a> / 
            <span><?php echo htmlspecialchars($order['order_number']); ?></span>
        </div>

        <h1>Order Details</h1>

        <div class="order-details-container">
            <div class="order-header-section">
                <div class="detail-box">
                    <h3>Order Information</h3>
                    <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                    <p><strong>Status:</strong> <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
                    <p><strong>Payment Status:</strong> <span class="status-badge status-<?php echo $order['payment_status']; ?>"><?php echo ucfirst($order['payment_status']); ?></span></p>
                </div>

                <div class="detail-box">
                    <h3>Shipping Address</h3>
                    <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>
            </div>

            <h2>Items Ordered</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="order-summary-box">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($order['total_amount'] - $order['shipping_cost'], 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>$<?php echo number_format($order['shipping_cost'], 2); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="account.php?tab=orders" class="btn btn-outline">Back to Orders</a>
            </div>
        </div>
    </div>
</section>

<style>
.order-details-container {
    background-color: var(--bg-white);
    padding: 30px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.order-header-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.detail-box {
    padding: 20px;
    background-color: var(--bg-light);
    border-radius: 8px;
}

.detail-box h3 {
    margin-bottom: 15px;
    color: var(--primary-color);
}

.detail-box p {
    margin: 10px 0;
    line-height: 1.8;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: var(--bg-light);
    border-radius: 8px;
    overflow: hidden;
}

.items-table th,
.items-table td {
    padding: 12px 15px;
    text-align: left;
}

.items-table th {
    background-color: var(--secondary-color);
    color: white;
    font-weight: bold;
}

.items-table tr:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.02);
}

.order-summary-box {
    background-color: var(--bg-light);
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    margin-left: auto;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-row.total {
    border-bottom: 2px solid var(--primary-color);
    font-weight: bold;
    font-size: 18px;
    padding: 15px 0;
    margin-top: 10px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.action-buttons .btn {
    flex: 1;
    max-width: 200px;
}
</style>

<?php include 'footer.php'; ?>
