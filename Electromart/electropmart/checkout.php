<?php
require_once 'config.php';

if (!is_user_logged_in()) {
    redirect(SITE_URL . 'login.php');
}

$page_title = 'Checkout';
include 'header.php';

$user_id = get_user_id();

// Get cart items
$cart_items = array();
$subtotal = 0;

$result = $conn->query("
    SELECT c.id, c.product_id, c.quantity, c.price, p.name, p.image_url
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id
    ORDER BY c.added_at DESC
");

while ($item = $result->fetch_assoc()) {
    $cart_items[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
}

if (empty($cart_items)) {
    redirect(SITE_URL . 'cart.php');
}

// Get user info
$user = $conn->query("SELECT full_name, email, phone, address, city, state, postal_code, country FROM users WHERE id = $user_id")->fetch_assoc();

$shipping = 50;
$tax = round($subtotal * 0.1, 2);
$total = $subtotal + $shipping + $tax;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $postal_code = sanitize($_POST['postal_code'] ?? '');
    $country = sanitize($_POST['country'] ?? '');
    $payment_method = sanitize($_POST['payment_method'] ?? 'card');

    if (empty($full_name) || empty($email) || empty($address) || empty($city) || empty($postal_code)) {
        $error = 'All fields are required.';
    } else {
        // Update user info
        $conn->query("UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone', address = '$address', city = '$city', state = '$state', postal_code = '$postal_code', country = '$country' WHERE id = $user_id");

        // Create order
        $order_number = 'ORD-' . date('YmdHis') . '-' . $user_id;
        
        $conn->query("INSERT INTO orders (user_id, order_number, total_amount, discount, shipping_cost, payment_method, shipping_address) VALUES ($user_id, '$order_number', $total, 0, $shipping, '$payment_method', '$address, $city, $state $postal_code, $country')");

        $order_id = $conn->insert_id;

        // Add order items
        foreach ($cart_items as $item) {
            $subtotal_item = $item['price'] * $item['quantity'];
            $conn->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) VALUES ($order_id, " . $item['product_id'] . ", '" . addslashes($item['name']) . "', " . $item['quantity'] . ", " . $item['price'] . ", $subtotal_item)");
        }

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        // Redirect to success page
        redirect(SITE_URL . 'order-confirmation.php?order_id=' . $order_id);
    }
}
?>

<section class="section">
    <div class="container">
        <h1>Checkout</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="checkout-form">
                <h2>Shipping Address</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($user['address']); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" required value="<?php echo htmlspecialchars($user['city']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="state">State/Province</label>
                            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" required value="<?php echo htmlspecialchars($user['postal_code']); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">
                    </div>

                    <h2 style="margin-top: 30px;">Payment Method</h2>

                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card" checked>
                            <span>Credit/Debit Card</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="paypal">
                            <span>PayPal</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="bank">
                            <span>Bank Transfer</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 30px;">Complete Order</button>
                </form>
            </div>

            <div class="checkout-summary">
                <h2>Order Summary</h2>
                <div class="order-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p><?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div class="item-price">
                                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-section">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
