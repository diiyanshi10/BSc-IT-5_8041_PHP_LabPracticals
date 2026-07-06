<?php
require_once 'config.php';

if (is_user_logged_in()) {
    redirect(SITE_URL . 'account.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        $result = $conn->query("SELECT id, username, email, password, role FROM users WHERE email = '$email' AND is_active = TRUE");
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify_custom($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
                // Redirect to admin or account based on role
                $redirect_url = $user['role'] === 'admin' ? 'admin/' : 'account.php';
                redirect(SITE_URL . $redirect_url);
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$page_title = 'Login';
include 'header.php';
?>

<section class="section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-form">
                <h1>Login to Your Account</h1>
                <p>Access your orders, wishlist, and account settings</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="you@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>

                <div class="auth-links">
                    <p>Don't have an account? <a href="register.php">Sign up here</a></p>
                    <p><a href="forgot-password.php">Forgot your password?</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
