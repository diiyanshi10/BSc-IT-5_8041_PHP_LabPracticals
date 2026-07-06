<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!is_user_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit;
}

$user_id = get_user_id();
$wishlist_id = intval($_POST['wishlist_id'] ?? 0);

$conn->query("DELETE FROM wishlist WHERE id = $wishlist_id AND user_id = $user_id");

echo json_encode(['success' => true, 'message' => 'Removed from wishlist']);
?>
