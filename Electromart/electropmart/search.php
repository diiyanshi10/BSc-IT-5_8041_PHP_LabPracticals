<?php
require_once 'config.php';

$query = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 12;

$page_title = 'Search Results';
include 'header.php';

if (empty($query)) {
    redirect(SITE_URL);
}

$offset = ($page - 1) * $per_page;

// Count total results
$total = $conn->query("SELECT COUNT(*) as count FROM products WHERE (name LIKE '%$query%' OR description LIKE '%$query%') AND is_active = TRUE")->fetch_assoc();
$total_pages = ceil($total['count'] / $per_page);

$products = $conn->query("
    SELECT id, name, price, discount_price, image_url, rating, reviews_count
    FROM products
    WHERE (name LIKE '%$query%' OR description LIKE '%$query%') AND is_active = TRUE
    ORDER BY name
    LIMIT $per_page OFFSET $offset
");
?>

<section class="section">
    <div class="container">
        <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
        <p><?php echo $total['count']; ?> products found</p>

        <div class="products-grid">
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()):
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
                            <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px 0;">
                    <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h2>No products found</h2>
                    <p>Try searching with different keywords</p>
                    <a href="<?php echo SITE_URL; ?>" class="btn btn-primary" style="margin-top: 20px;">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="pagination-current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="search.php?q=<?php echo urlencode($query); ?>&page=<?php echo $i; ?>" class="pagination-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
