<?php
require_once 'config.php';
$page_title = 'Best Deals';
include 'header.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Get all discounted products
$total = $conn->query("SELECT COUNT(*) as count FROM products WHERE discount_price IS NOT NULL AND is_active = TRUE")->fetch_assoc();
$total_pages = ceil($total['count'] / $per_page);

$products = $conn->query("
    SELECT id, name, price, discount_price, image_url, rating, reviews_count,
           ROUND(((price - discount_price) / price) * 100) as discount_percent
    FROM products
    WHERE discount_price IS NOT NULL AND is_active = TRUE
    ORDER BY discount_percent DESC
    LIMIT $per_page OFFSET $offset
");
?>

<section class="section">
    <div class="container">
        <h1>Best Deals & Discounts</h1>
        <p>Save big on premium electronics!</p>

        <div class="products-grid">
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <span class="discount-badge">-<?php echo $product['discount_percent']; ?>%</span>
                        </div>
                        <div class="product-info">
                            <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                            <div class="rating">
                                <span class="stars">
                                    <?php for ($i = 0; $i < 5; $i++) {
                                        if ($i < floor($product['rating'])) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    } ?>
                                </span>
                                <span class="review-count">(<?php echo $product['reviews_count']; ?>)</span>
                            </div>
                            <div class="price">
                                <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                                <span class="sale-price">$<?php echo number_format($product['discount_price'], 2); ?></span>
                            </div>
                            <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No deals available at the moment.</p>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="pagination-current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="deals.php?page=<?php echo $i; ?>" class="pagination-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
