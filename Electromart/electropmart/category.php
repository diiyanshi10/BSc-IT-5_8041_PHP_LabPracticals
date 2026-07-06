<?php
require_once 'config.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sort = $_GET['sort'] ?? 'newest';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 12;

$category = $conn->query("SELECT name FROM categories WHERE id = $category_id AND is_active = TRUE")->fetch_assoc();

if (!$category) {
    redirect(SITE_URL);
}

$page_title = htmlspecialchars($category['name']);
include 'header.php';

// Build query based on sort
$order_by = "p.created_at DESC";
if ($sort === 'price-low') {
    $order_by = "p.price ASC";
} elseif ($sort === 'price-high') {
    $order_by = "p.price DESC";
} elseif ($sort === 'rating') {
    $order_by = "p.rating DESC";
} elseif ($sort === 'popular') {
    $order_by = "p.reviews_count DESC";
}

$offset = ($page - 1) * $per_page;

// Count total products
$total = $conn->query("SELECT COUNT(*) as count FROM products WHERE category_id = $category_id AND is_active = TRUE")->fetch_assoc();
$total_pages = ceil($total['count'] / $per_page);

$products = $conn->query("
    SELECT id, name, price, discount_price, image_url, rating, reviews_count
    FROM products
    WHERE category_id = $category_id AND is_active = TRUE
    ORDER BY $order_by
    LIMIT $per_page OFFSET $offset
");
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo SITE_URL; ?>">Home</a> / 
            <span><?php echo htmlspecialchars($category['name']); ?></span>
        </div>

        <h1><?php echo htmlspecialchars($category['name']); ?></h1>

        <!-- Filters and Sorting -->
        <div class="filters-section">
            <div class="sort-options">
                <label for="sort">Sort by:</label>
                <select id="sort" onchange="location.href='category.php?id=<?php echo $category_id; ?>&sort=' + this.value">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="price-low" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price-high" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Top Rated</option>
                    <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Reviewed</option>
                </select>
            </div>

            <div class="view-options">
                <span><?php echo $total['count']; ?> products found</span>
            </div>
        </div>

        <!-- Products Grid -->
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
                            <div class="product-actions">
                                <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Add
                                </button>
                                <button class="btn btn-sm btn-outline add-to-wishlist" data-product-id="<?php echo $product['id']; ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No products found in this category.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="category.php?id=<?php echo $category_id; ?>&page=1&sort=<?php echo $sort; ?>" class="pagination-link">&laquo; First</a>
                    <a href="category.php?id=<?php echo $category_id; ?>&page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>" class="pagination-link">&lsaquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="pagination-current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="category.php?id=<?php echo $category_id; ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" class="pagination-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="category.php?id=<?php echo $category_id; ?>&page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>" class="pagination-link">Next &rsaquo;</a>
                    <a href="category.php?id=<?php echo $category_id; ?>&page=<?php echo $total_pages; ?>&sort=<?php echo $sort; ?>" class="pagination-link">Last &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
