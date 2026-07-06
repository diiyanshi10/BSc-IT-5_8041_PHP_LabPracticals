<?php
require_once 'config.php';

if (is_user_logged_in()) {
    redirect(SITE_URL . 'account.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = sanitize($_POST['full_name'] ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($full_name)) {
        $error = 'All fields are required.';
    } elseif (!validate_email($email)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email exists
        $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
        
        if ($check->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            // Create new user
            $hashed_password = password_hash_custom($password);
            
            if ($conn->query("INSERT INTO users (username, email, password, full_name) VALUES ('$username', '$email', '$hashed_password', '$full_name')")) {
                $success = 'Account created successfully! You can now <a href="login.php">login here</a>.';
            } else {
                $error = 'Error creating account. Please try again.';
            }
        }
    }
}

$page_title = 'Register';
include 'header.php';
?>

<section class="section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-form">
                <h1>Create Your Account</h1>
                <p>Join Electropmart and start shopping today</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required placeholder="John Doe" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="johndoe" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="you@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••" minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••" minlength="6">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                </form>

                <div class="auth-links">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
