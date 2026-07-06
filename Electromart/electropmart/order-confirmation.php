<?php
require_once 'config.php';

if (!is_user_logged_in()) {
    redirect(SITE_URL . 'login.php');
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$user_id = get_user_id();

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id")->fetch_assoc();

if (!$order) {
    redirect(SITE_URL);
}

$page_title = 'Order Confirmation';
include 'header.php';

$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<section class="section">
    <div class="container">
        <div class="order-confirmation">
            <div class="confirmation-header">
                <i class="fas fa-check-circle"></i>
                <h1>Order Confirmed!</h1>
                <p>Thank you for your purchase. Your order has been received.</p>
            </div>

            <div class="order-details">
                <div class="detail-section">
                    <h3>Order Number</h3>
                    <p><?php echo htmlspecialchars($order['order_number']); ?></p>
                </div>

                <div class="detail-section">
                    <h3>Order Date</h3>
                    <p><?php echo date('F d, Y \a\t h:i A', strtotime($order['created_at'])); ?></p>
                </div>

                <div class="detail-section">
                    <h3>Order Total</h3>
                    <p class="order-total">$<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>

                <div class="detail-section">
                    <h3>Status</h3>
                    <p><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
                </div>
            </div>

            <div class="order-items-section">
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
            </div>

            <div class="order-summary">
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

            <div class="shipping-address">
                <h3>Shipping Address</h3>
                <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>

            <div class="action-buttons">
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Continue Shopping</a>
                <a href="account.php" class="btn btn-outline">View My Orders</a>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
