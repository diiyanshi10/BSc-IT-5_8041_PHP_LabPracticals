<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!is_user_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to wishlist']);
    exit;
}

$user_id = get_user_id();
$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}

// Check if product exists
$product = $conn->query("SELECT id FROM products WHERE id = $product_id AND is_active = TRUE")->fetch_assoc();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check if already in wishlist
$existing = $conn->query("SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $product_id")->fetch_assoc();

if ($existing) {
    // Remove from wishlist
    $conn->query("DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
    echo json_encode(['success' => true, 'message' => 'Removed from wishlist']);
} else {
    // Add to wishlist
    $conn->query("INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)");
    echo json_encode(['success' => true, 'message' => 'Added to wishlist']);
}
?>
