<?php
require_once 'config.php';
$page_title = 'Contact Us';
include 'header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!validate_email($email)) {
        $error = 'Invalid email address.';
    } else {
        // In production, send actual email
        $success = 'Thank you for your message. We will get back to you soon!';
        // mail(SITE_EMAIL, 'New Contact: ' . $subject, $message, 'From: ' . $email);
    }
}
?>

<section class="section">
    <div class="container">
        <h1>Contact Us</h1>
        <p class="section-subtitle">We'd love to hear from you. Send us a message!</p>

        <div class="contact-container">
            <div class="contact-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required placeholder="Your Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required placeholder="What's this about?" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required placeholder="Your message here..." rows="6"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>
            </div>

            <div class="contact-info">
                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Address</h3>
                    <p>123 Electronics Street<br>Tech City, TC 12345<br>United States</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-phone"></i>
                    <h3>Phone</h3>
                    <p><a href="tel:+15551234567">+1 (555) 123-4567</a></p>
                    <p>Monday - Friday, 9AM - 6PM EST</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p><a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></p>
                    <p>We typically respond within 24 hours</p>
                </div>

                <div class="info-card">
                    <i class="fas fa-comments"></i>
                    <h3>Live Chat</h3>
                    <p>Chat with us during business hours</p>
                    <p>Available Monday - Friday, 9AM - 6PM EST</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.section-subtitle {
    text-align: center;
    color: var(--text-light);
    font-size: 18px;
    margin-bottom: 40px;
}

.contact-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 40px;
}

.contact-form {
    background-color: var(--bg-white);
    padding: 30px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.contact-info {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.info-card {
    background-color: var(--bg-white);
    padding: 20px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    text-align: center;
}

.info-card i {
    font-size: 32px;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.info-card h3 {
    margin-bottom: 10px;
    color: var(--text-dark);
}

.info-card p {
    margin: 8px 0;
    color: var(--text-light);
}

.info-card a {
    color: var(--primary-color);
    text-decoration: none;
}

.info-card a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .contact-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .contact-form {
        padding: 20px;
    }

    .info-card {
        padding: 15px;
    }
}
</style>

<?php include 'footer.php'; ?>
