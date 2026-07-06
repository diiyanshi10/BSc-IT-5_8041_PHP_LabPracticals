<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Orders';
$action = $_GET['action'] ?? 'list';
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? '';
    
    if ($action_type === 'update_status' && $order_id > 0) {
        $status = sanitize($_POST['status'] ?? '');
        $payment_status = sanitize($_POST['payment_status'] ?? '');
        
        if (in_array($status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            $conn->query("UPDATE orders SET status='$status', payment_status='$payment_status' WHERE id=$order_id");
            $success = 'Order updated successfully!';
            $action = 'list';
        }
    }
}

include '../header.php';
?>

<section class="section">
    <div class="container">
        <div class="admin-header">
            <h1>Order Management</h1>
            <?php if ($action !== 'list'): ?>
                <a href="?action=list" class="btn btn-outline">Back to List</a>
            <?php endif; ?>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- List View -->
        <?php if ($action === 'list'): ?>
            <div class="admin-filters">
                <form method="GET" class="filter-form">
                    <input type="hidden" name="action" value="list">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="processing" <?php echo ($_GET['status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="shipped" <?php echo ($_GET['status'] ?? '') === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo ($_GET['status'] ?? '') === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                    </select>
                </form>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT o.*, u.full_name, u.email FROM orders o
                                  LEFT JOIN users u ON o.user_id = u.id";
                        
                        if (isset($_GET['status']) && !empty($_GET['status'])) {
                            $status = sanitize($_GET['status']);
                            $query .= " WHERE o.status = '$status'";
                        }
                        
                        $query .= " ORDER BY o.created_at DESC";
                        
                        $orders = $conn->query($query);

                        if ($orders->num_rows > 0):
                            while ($order = $orders->fetch_assoc()):
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($order['full_name']); ?></div>
                                    <small><?php echo htmlspecialchars($order['email']); ?></small>
                                </td>
                                <td>
                                    <strong>$<?php echo number_format($order['total_amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge payment-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </td>
                                <td>
                                    <a href="?action=view&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">No orders found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- View/Edit Order -->
        <?php elseif ($action === 'view' && $order_id > 0): ?>
            <?php
            $order = $conn->query("SELECT o.*, u.* FROM orders o 
                                  LEFT JOIN users u ON o.user_id = u.id 
                                  WHERE o.id = $order_id")->fetch_assoc();
            
            if (!$order) {
                echo '<p>Order not found</p>';
            } else {
                $items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
            ?>
            
            <div class="order-detail-container">
                <div class="order-info-section">
                    <div class="info-box">
                        <h2><?php echo htmlspecialchars($order['order_number']); ?></h2>
                        <div class="info-row">
                            <span>Customer:</span>
                            <strong><?php echo htmlspecialchars($order['full_name']); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Email:</span>
                            <strong><?php echo htmlspecialchars($order['email']); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Phone:</span>
                            <strong><?php echo htmlspecialchars($order['phone']); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Date:</span>
                            <strong><?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></strong>
                        </div>
                    </div>

                    <form method="POST" class="info-box">
                        <input type="hidden" name="action_type" value="update_status">
                        <h3>Order Status</h3>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Payment Status</label>
                            <select name="payment_status" required>
                                <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo $order['payment_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                <option value="refunded" <?php echo $order['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>

                    <div class="info-box">
                        <h3>Shipping Address</h3>
                        <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    </div>
                </div>

                <div class="order-items-section">
                    <div class="info-box">
                        <h3>Order Items</h3>
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
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

                    <div class="info-box summary-box">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <strong>$<?php echo number_format($order['total_amount'] - $order['shipping_cost'], 2); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <strong>$<?php echo number_format($order['shipping_cost'], 2); ?></strong>
                        </div>
                        <div class="summary-row">
                            <span>Discount:</span>
                            <strong>$<?php echo number_format($order['discount'], 2); ?></strong>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <strong>$<?php echo number_format($order['total_amount'], 2); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>

        <?php endif; ?>
    </div>
</section>

<style>
.admin-filters {
    margin-bottom: 20px;
}

.filter-form {
    display: flex;
    gap: 10px;
}

.filter-form select {
    padding: 10px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    background-color: var(--bg-white);
    font-size: 14px;
}

.order-detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.order-info-section,
.order-items-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-box {
    background-color: var(--bg-white);
    padding: 20px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.info-box h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: var(--primary-color);
}

.info-box h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-row span {
    color: var(--text-light);
}

.info-row strong {
    color: var(--text-dark);
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
}

.order-items-table th {
    background-color: var(--bg-light);
    padding: 10px;
    text-align: left;
    font-weight: bold;
    border-bottom: 2px solid var(--border-color);
}

.order-items-table td {
    padding: 10px;
    border-bottom: 1px solid var(--border-color);
}

.summary-box .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-box .summary-row.total {
    border-bottom: 2px solid var(--primary-color);
    font-size: 18px;
    font-weight: bold;
    margin-top: 10px;
    padding-top: 10px;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    color: white;
}

.status-pending { background-color: #F39C12; }
.status-confirmed { background-color: #3498DB; }
.status-processing { background-color: #9B59B6; }
.status-shipped { background-color: #16A085; }
.status-delivered { background-color: #27AE60; }
.status-cancelled { background-color: #E74C3C; }

.payment-pending { background-color: #F39C12; }
.payment-completed { background-color: #27AE60; }
.payment-failed { background-color: #E74C3C; }
.payment-refunded { background-color: #95A5A6; }

@media (max-width: 768px) {
    .order-detail-container {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../footer.php'; ?>
