<?php
require_once 'config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id == 0) {
    redirect(SITE_URL);
}

$product = $conn->query("
    SELECT p.*, c.name as category_name, c.id as category_id
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = $product_id AND p.is_active = TRUE
")->fetch_assoc();

if (!$product) {
    redirect(SITE_URL);
}

$page_title = htmlspecialchars($product['name']);
include 'header.php';

$images = $conn->query("SELECT image_url, alt_text FROM product_images WHERE product_id = $product_id ORDER BY sort_order");
$reviews = $conn->query("SELECT r.*, u.full_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = $product_id AND r.is_active = TRUE ORDER BY r.created_at DESC LIMIT 5");
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Home</a> / 
            <a href="category.php?id=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> / 
            <span><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="product-detail">
            <!-- Product Images -->
            <div class="product-gallery">
                <div class="main-image">
                    <img id="main-image" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="thumbnail-images">
                    <img class="thumbnail active" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Main">
                    <?php while ($img = $images->fetch_assoc()): ?>
                        <img class="thumbnail" src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="<?php echo htmlspecialchars($img['alt_text']); ?>">
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Product Details -->
            <div class="product-details">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="rating-section">
                    <div class="stars">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < floor($product['rating'])) {
                                echo '<i class="fas fa-star"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                    <span><?php echo round($product['rating'], 1); ?> out of 5</span>
                    <span><?php echo $product['reviews_count']; ?> customer reviews</span>
                </div>

                <div class="price-section">
                    <?php
                    $discount = $product['discount_price'] ? round((($product['price'] - $product['discount_price']) / $product['price']) * 100) : 0;
                    ?>
                    <?php if ($product['discount_price']): ?>
                        <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                        <span class="sale-price">$<?php echo number_format($product['discount_price'], 2); ?></span>
                        <span class="discount-label">-<?php echo $discount; ?>%</span>
                    <?php else: ?>
                        <span class="sale-price">$<?php echo number_format($product['price'], 2); ?></span>
                    <?php endif; ?>
                </div>

                <div class="description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="stock-info">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock']; ?> available)</span>
                    <?php else: ?>
                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="product-actions">
                    <?php if ($product['stock'] > 0): ?>
                        <div class="quantity-selector">
                            <label>Quantity:</label>
                            <div class="quantity-input">
                                <button type="button" id="decrease-qty">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                <button type="button" id="increase-qty">+</button>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-lg add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    <?php else: ?>
                        <button class="btn btn-lg" disabled>Out of Stock</button>
                    <?php endif; ?>
                    <button class="btn btn-outline btn-lg add-to-wishlist" data-product-id="<?php echo $product['id']; ?>">
                        <i class="far fa-heart"></i> Add to Wishlist
                    </button>
                </div>

                <div class="shipping-info">
                    <h3>Shipping & Returns</h3>
                    <ul>
                        <li><i class="fas fa-truck"></i> Free shipping on orders over $50</li>
                        <li><i class="fas fa-undo"></i> 30-day easy returns</li>
                        <li><i class="fas fa-shield-alt"></i> 100% secure checkout</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <h2>Customer Reviews</h2>
            <div class="reviews-list">
                <?php if ($reviews->num_rows > 0): ?>
                    <?php while ($review = $reviews->fetch_assoc()): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <strong><?php echo htmlspecialchars($review['full_name']); ?></strong>
                                <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 0; $i < 5; $i++) {
                                    if ($i < $review['rating']) {
                                        echo '<i class="fas fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                } ?>
                            </div>
                            <?php if ($review['title']): ?>
                                <h4><?php echo htmlspecialchars($review['title']); ?></h4>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($review['review']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products -->
        <section class="section">
            <h2 class="section-title">Related Products</h2>
            <div class="products-grid">
                <?php
                $related = $conn->query("
                    SELECT id, name, price, discount_price, image_url, rating, reviews_count
                    FROM products
                    WHERE category_id = " . $product['category_id'] . " AND id != $product_id AND is_active = TRUE
                    LIMIT 8
                ");
                
                while ($rel = $related->fetch_assoc()):
                    $disc = $rel['discount_price'] ? round((($rel['price'] - $rel['discount_price']) / $rel['price']) * 100) : 0;
                ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($rel['image_url']); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>">
                            <?php if ($disc > 0): ?>
                                <span class="discount-badge">-<?php echo $disc; ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><a href="product.php?id=<?php echo $rel['id']; ?>"><?php echo htmlspecialchars($rel['name']); ?></a></h3>
                            <div class="price">
                                <?php if ($rel['discount_price']): ?>
                                    <span class="original-price">$<?php echo number_format($rel['price'], 2); ?></span>
                                    <span class="sale-price">$<?php echo number_format($rel['discount_price'], 2); ?></span>
                                <?php else: ?>
                                    <span class="sale-price">$<?php echo number_format($rel['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $rel['id']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
</section>

<script>
document.getElementById('main-image').addEventListener('click', function() {
    this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)';
});

document.querySelectorAll('.thumbnail').forEach(img => {
    img.addEventListener('click', function() {
        document.getElementById('main-image').src = this.src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

document.getElementById('increase-qty').addEventListener('click', function() {
    let qty = document.getElementById('quantity');
    qty.value = Math.min(parseInt(qty.value) + 1, parseInt(qty.max));
});

document.getElementById('decrease-qty').addEventListener('click', function() {
    let qty = document.getElementById('quantity');
    qty.value = Math.max(parseInt(qty.value) - 1, 1);
});
</script>

<?php include 'footer.php'; ?>
