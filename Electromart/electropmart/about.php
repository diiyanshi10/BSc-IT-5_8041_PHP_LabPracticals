<?php
require_once 'config.php';
$page_title = 'About Us';
include 'header.php';
?>

<section class="section">
    <div class="container">
        <h1>About Electropmart</h1>

        <div class="about-content">
            <div class="about-section">
                <h2>Welcome to Electropmart</h2>
                <p>
                    Electropmart is your trusted online destination for premium electronics and gadgets. 
                    Since our launch, we've been committed to providing customers with the highest quality products, 
                    competitive prices, and exceptional customer service.
                </p>
            </div>

            <div class="about-section">
                <h2>Our Mission</h2>
                <p>
                    Our mission is to make cutting-edge electronics accessible to everyone by offering:
                </p>
                <ul class="mission-list">
                    <li>Wide selection of authentic products</li>
                    <li>Competitive pricing and exclusive deals</li>
                    <li>Fast and reliable shipping</li>
                    <li>Exceptional customer support</li>
                    <li>Secure and hassle-free shopping</li>
                </ul>
            </div>

            <div class="about-section">
                <h2>Why Choose Us?</h2>
                <div class="reasons-grid">
                    <div class="reason-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Authentic Products</h3>
                        <p>All products are 100% genuine and verified</p>
                    </div>
                    <div class="reason-card">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Best Prices</h3>
                        <p>Competitive pricing with frequent discounts</p>
                    </div>
                    <div class="reason-card">
                        <i class="fas fa-truck"></i>
                        <h3>Fast Shipping</h3>
                        <p>Quick delivery to your doorstep</p>
                    </div>
                    <div class="reason-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Secure Shopping</h3>
                        <p>100% secure payment and data protection</p>
                    </div>
                </div>
            </div>

            <div class="about-section">
                <h2>Our Values</h2>
                <ul class="values-list">
                    <li><strong>Integrity:</strong> We believe in honest business practices and transparent dealings</li>
                    <li><strong>Quality:</strong> We prioritize quality in both products and services</li>
                    <li><strong>Customer Focus:</strong> Your satisfaction is our top priority</li>
                    <li><strong>Innovation:</strong> We continuously improve our platform and offerings</li>
                </ul>
            </div>

            <div class="about-section">
                <h2>Contact Us</h2>
                <p>Have questions? We'd love to hear from you!</p>
                <p>
                    <strong>Email:</strong> <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a><br>
                    <strong>Phone:</strong> +1 (555) 123-4567<br>
                    <strong>Hours:</strong> Monday - Friday, 9AM - 6PM EST
                </p>
            </div>
        </div>
    </div>
</section>

<style>
.about-content {
    background-color: var(--bg-white);
    padding: 40px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    line-height: 1.8;
}

.about-section {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid var(--border-color);
}

.about-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.about-section h2 {
    color: var(--primary-color);
    margin-bottom: 20px;
    font-size: 24px;
}

.about-section p {
    margin-bottom: 15px;
    color: var(--text-dark);
}

.mission-list,
.values-list {
    list-style: none;
    padding-left: 0;
    margin: 15px 0;
}

.mission-list li,
.values-list li {
    padding: 10px 0;
    padding-left: 30px;
    position: relative;
}

.mission-list li:before,
.values-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--success-color);
    font-weight: bold;
    font-size: 18px;
}

.reasons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.reason-card {
    background-color: var(--bg-light);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
}

.reason-card i {
    font-size: 36px;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.reason-card h3 {
    margin: 15px 0 10px 0;
    color: var(--text-dark);
}

.reason-card p {
    margin: 0;
    color: var(--text-light);
    font-size: 14px;
}

@media (max-width: 768px) {
    .about-content {
        padding: 20px;
    }

    .reasons-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>
