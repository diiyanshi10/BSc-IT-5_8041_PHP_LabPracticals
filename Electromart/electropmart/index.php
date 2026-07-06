<?php
require_once 'config.php';
$page_title = 'Home';
include 'header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Electropmart</h1>
        <p>Discover the Latest Electronics at Unbeatable Prices</p>
        <a href="<?php echo SITE_URL; ?>deals.php" class="btn btn-primary">Shop Now</a>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="products-grid">
            <?php
            $featured_products = $conn->query("
                SELECT id, name, price, discount_price, image_url, rating, reviews_count 
                FROM products 
                WHERE is_featured = TRUE AND is_active = TRUE 
                LIMIT 12
            ");

            if ($featured_products->num_rows > 0):
                while ($product = $featured_products->fetch_assoc()):
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
                            <span class="review-count">(<?php echo $product['reviews_count']; ?> reviews)</span>
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
                            <button class="btn btn-sm btn-outline add-to-wishlist" data-product-id="<?php echo $product['id']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <p class="text-center">No featured products available.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="categories-grid">
            <?php
            $categories = $conn->query("
                SELECT id, name, slug, image_url, 
                       (SELECT COUNT(*) FROM products WHERE category_id = categories.id AND is_active = TRUE) as product_count
                FROM categories 
                WHERE is_active = TRUE 
                LIMIT 8
            ");

            while ($category = $categories->fetch_assoc()):
            ?>
                <a href="category.php?id=<?php echo $category['id']; ?>" class="category-card">
                    <div class="category-image">
                        <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                    </div>
                    <div class="category-info">
                        <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p><?php echo $category['product_count']; ?> products</p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Special Offer Section -->
<section class="section">
    <div class="container">
        <div class="offer-banner">
            <h2>Summer Sale - Up to 50% Off!</h2>
            <p>Limited time offer on selected electronics</p>
            <a href="deals.php" class="btn btn-primary btn-lg">View Deals</a>
        </div>
    </div>
</section>

<!-- Why Shop With Us -->
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title">Why Shop With Us?</h2>
        <div class="features-grid">
            <div class="feature">
                <i class="fas fa-truck"></i>
                <h3>Fast Shipping</h3>
                <p>Free shipping on orders over $50</p>
            </div>
            <div class="feature">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure Checkout</h3>
                <p>100% secure payment processing</p>
            </div>
            <div class="feature">
                <i class="fas fa-undo"></i>
                <h3>Easy Returns</h3>
                <p>30-day return policy</p>
            </div>
            <div class="feature">
                <i class="fas fa-headset"></i>
                <h3>24/7 Support</h3>
                <p>Dedicated customer service team</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
