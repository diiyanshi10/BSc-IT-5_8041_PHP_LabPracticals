<?php
// Header file included in all pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Header -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="<?php echo SITE_URL; ?>">
                            <i class="fas fa-bolt"></i> <strong>Electropmart</strong>
                        </a>
                    </div>

                    <div class="search-box">
                        <form method="GET" action="<?php echo SITE_URL; ?>search.php">
                            <input type="text" name="q" placeholder="Search for electronics..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <div class="header-actions">
                        <?php if (is_user_logged_in()): ?>
                            <a href="<?php echo SITE_URL; ?>account.php" class="action-btn">
                                <i class="fas fa-user"></i>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo SITE_URL; ?>login.php" class="action-btn">
                                <i class="fas fa-user"></i>
                                <span>Login</span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo SITE_URL; ?>wishlist.php" class="action-btn">
                            <i class="fas fa-heart"></i>
                            <span>Wishlist</span>
                        </a>
                        
                        <a href="<?php echo SITE_URL; ?>cart.php" class="action-btn">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Cart</span>
                            <span class="badge"><?php echo get_cart_count(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="navbar">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="dropdown">
                        <a href="#">Categories <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <?php
                            global $conn;
                            $categories = $conn->query("SELECT id, name, slug FROM categories WHERE is_active = TRUE ORDER BY name");
                            while ($cat = $categories->fetch_assoc()):
                            ?>
                                <li><a href="<?php echo SITE_URL; ?>category.php?id=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                    <li><a href="<?php echo SITE_URL; ?>deals.php">Best Deals</a></li>
                    <li><a href="<?php echo SITE_URL; ?>about.php">About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>contact.php">Contact</a></li>
                    <?php if (is_admin()): ?>
                        <li><a href="<?php echo SITE_URL; ?>admin/">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main class="main-content">
