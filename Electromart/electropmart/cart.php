<?php
require_once 'config.php';
$page_title = 'Shopping Cart';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    $product = $conn->query("SELECT id, price, discount_price, stock FROM products WHERE id = $product_id AND is_active = TRUE")->fetch_assoc();
    
    if ($product && $quantity > 0 && $quantity <= $product['stock']) {
        $price = $product['discount_price'] ?? $product['price'];
        
        if (is_user_logged_in()) {
            $user_id = get_user_id();
            $conn->query("INSERT INTO cart (user_id, product_id, quantity, price) VALUES ($user_id, $product_id, $quantity, $price) 
                         ON DUPLICATE KEY UPDATE quantity = quantity + $quantity");
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = array('quantity' => $quantity, 'price' => $price);
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        exit;
    }
}

// Handle remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $cart_id = intval($_POST['cart_id']);
    
    if (is_user_logged_in()) {
        $user_id = get_user_id();
        $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    } else {
        $product_id = intval($_POST['product_id']);
        unset($_SESSION['cart'][$product_id]);
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

include 'header.php';

// Get cart items
$cart_items = array();
$subtotal = 0;

if (is_user_logged_in()) {
    $user_id = get_user_id();
    $result = $conn->query("
        SELECT c.id, c.product_id, c.quantity, c.price, p.name, p.image_url, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id
        ORDER BY c.added_at DESC
    ");
    
    while ($item = $result->fetch_assoc()) {
        $cart_items[] = $item;
        $subtotal += $item['price'] * $item['quantity'];
    }
} else if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product = $conn->query("SELECT id, name, image_url, price, discount_price, stock FROM products WHERE id = $product_id")->fetch_assoc();
        if ($product) {
            $price = $item['price'];
            $cart_items[] = array(
                'id' => 0,
                'product_id' => $product_id,
                'quantity' => $item['quantity'],
                'price' => $price,
                'name' => $product['name'],
                'image_url' => $product['image_url'],
                'stock' => $product['stock']
            );
            $subtotal += $price * $item['quantity'];
        }
    }
}

$shipping = 50;
$tax = round($subtotal * 0.1, 2);
$total = $subtotal + $shipping + $tax;
?>

<section class="section">
    <div class="container">
        <h1>Shopping Cart</h1>

        <?php if (!empty($cart_items)): ?>
            <div class="cart-container">
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="cart-product">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            <div>
                                                <a href="product.php?id=<?php echo $item['product_id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" data-product-id="<?php echo $item['product_id']; ?>">
                                    </td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger remove-from-cart" data-cart-id="<?php echo $item['id']; ?>" data-product-id="<?php echo $item['product_id']; ?>">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (10%):</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                    <a href="<?php echo SITE_URL; ?>" class="btn btn-outline btn-block">Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your Cart is Empty</h2>
                <p>Add some products to your cart and come back here.</p>
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.querySelectorAll('.remove-from-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const cartId = this.dataset.cartId;
        const productId = this.dataset.productId;
        
        fetch('cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=remove&cart_id=' + cartId + '&product_id=' + productId
        }).then(() => location.reload());
    });
});
</script>

<?php include 'footer.php'; ?>
