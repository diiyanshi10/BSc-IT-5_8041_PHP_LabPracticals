<?php
require_once 'config.php';

if (!is_user_logged_in()) {
    redirect(SITE_URL . 'login.php');
}

$page_title = 'My Wishlist';
include 'header.php';

$user_id = get_user_id();

$wishlist = $conn->query("
    SELECT p.id, p.name, p.price, p.discount_price, p.image_url, p.rating, p.reviews_count, w.id as wishlist_id
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = $user_id
    ORDER BY w.created_at DESC
");
?>

<section class="section">
    <div class="container">
        <h1>My Wishlist</h1>

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
                            <div class="rating">
                                <span class="stars">
                                    <?php
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < floor($product['rating'])) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </span>
                                <span class="review-count">(<?php echo $product['reviews_count']; ?>)</span>
                            </div>
                            <div class="price">
                                <?php if ($product['discount_price']): ?>
                                    <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                                    <span class="sale-price">$<?php echo number_format($product['discount_price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="sale-price">$<?php echo number_format($product['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-actions">
                                <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button class="btn btn-sm btn-outline remove-from-wishlist" data-wishlist-id="<?php echo $product['wishlist_id']; ?>">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-heart"></i>
                <h2>Your Wishlist is Empty</h2>
                <p>Add products to your wishlist and they will appear here.</p>
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.querySelectorAll('.remove-from-wishlist').forEach(btn => {
    btn.addEventListener('click', function() {
        const wishlistId = this.dataset.wishlistId;
        fetch('api/remove-from-wishlist.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'wishlist_id=' + wishlistId
        }).then(() => location.reload());
    });
});
</script>

<?php include 'footer.php'; ?>
