<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Products';
$action = $_GET['action'] ?? 'list';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle product operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? '';

    if ($action_type === 'add' || $action_type === 'edit') {
        $name = sanitize($_POST['name'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $discount_price = floatval($_POST['discount_price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $description = sanitize($_POST['description'] ?? '');
        $image_url = sanitize($_POST['image_url'] ?? '');
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if (empty($name) || $category_id == 0 || $price <= 0) {
            $error = 'Please fill all required fields with valid values.';
        } else {
            if ($action_type === 'add') {
                $slug = strtolower(str_replace(' ', '-', $name)) . '-' . time();
                $conn->query("INSERT INTO products (name, slug, category_id, price, discount_price, stock, description, image_url, is_featured) 
                             VALUES ('$name', '$slug', $category_id, $price, $discount_price, $stock, '$description', '$image_url', $is_featured)");
                $success = 'Product added successfully!';
                $action = 'list';
            } elseif ($action_type === 'edit' && $product_id > 0) {
                $conn->query("UPDATE products SET name='$name', category_id=$category_id, price=$price, discount_price=$discount_price, 
                             stock=$stock, description='$description', image_url='$image_url', is_featured=$is_featured WHERE id=$product_id");
                $success = 'Product updated successfully!';
                $action = 'list';
            }
        }
    } elseif ($action_type === 'delete' && $product_id > 0) {
        $conn->query("DELETE FROM products WHERE id = $product_id");
        $success = 'Product deleted successfully!';
        $action = 'list';
    }
}

include '../header.php';
?>

<section class="section">
    <div class="container">
        <div class="admin-header">
            <h1>Product Management</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="btn btn-primary">+ Add New Product</a>
            <?php else: ?>
                <a href="?action=list" class="btn btn-outline">Back to List</a>
            <?php endif; ?>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- List View -->
        <?php if ($action === 'list'): ?>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Discount Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $products = $conn->query("
                            SELECT p.*, c.name as category_name FROM products p
                            LEFT JOIN categories c ON p.category_id = c.id
                            ORDER BY p.created_at DESC
                        ");

                        if ($products->num_rows > 0):
                            while ($product = $products->fetch_assoc()):
                        ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="table-thumb">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <?php echo $product['discount_price'] ? '$' . number_format($product['discount_price'], 2) : '-'; ?>
                                </td>
                                <td>
                                    <span class="stock-badge <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                        <?php echo $product['stock']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="featured-badge <?php echo $product['is_featured'] ? 'featured' : 'not-featured'; ?>">
                                        <?php echo $product['is_featured'] ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px;">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- Add/Edit Form -->
        <?php else: ?>
            <div class="admin-form-wrapper">
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>">
                    
                    <?php
                    if ($action === 'edit' && $product_id > 0) {
                        $product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
                        if (!$product) redirect('products.php');
                    }
                    ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>"
                                   placeholder="Enter product name">
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category *</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = $conn->query("SELECT id, name FROM categories WHERE is_active = TRUE ORDER BY name");
                                while ($cat = $categories->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo isset($product) && $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price ($) *</label>
                            <input type="number" id="price" name="price" required step="0.01" 
                                   value="<?php echo isset($product) ? $product['price'] : ''; ?>"
                                   placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="discount_price">Discount Price ($)</label>
                            <input type="number" id="discount_price" name="discount_price" step="0.01" 
                                   value="<?php echo isset($product) ? $product['discount_price'] : ''; ?>"
                                   placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="stock">Stock Quantity *</label>
                            <input type="number" id="stock" name="stock" required 
                                   value="<?php echo isset($product) ? $product['stock'] : ''; ?>"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" 
                                  placeholder="Enter product description"><?php echo isset($product) ? htmlspecialchars($product['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input type="url" id="image_url" name="image_url" 
                               value="<?php echo isset($product) ? htmlspecialchars($product['image_url']) : ''; ?>"
                               placeholder="https://example.com/image.jpg">
                        <?php if (isset($product) && $product['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="image-preview">
                        <?php endif; ?>
                    </div>

                    <div class="form-group checkbox">
                        <input type="checkbox" id="is_featured" name="is_featured" 
                               <?php echo isset($product) && $product['is_featured'] ? 'checked' : ''; ?>>
                        <label for="is_featured">Featured Product</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?php echo $action === 'edit' ? 'Update Product' : 'Add Product'; ?>
                        </button>
                        <a href="?action=list" class="btn btn-outline btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.admin-header h1 {
    margin: 0;
}

.admin-table-wrapper {
    background-color: var(--bg-white);
    border-radius: 8px;
    overflow: auto;
    box-shadow: var(--shadow);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background-color: var(--secondary-color);
    color: white;
    padding: 15px;
    text-align: left;
    font-weight: bold;
}

.admin-table td {
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color);
}

.admin-table tr:hover {
    background-color: var(--bg-light);
}

.table-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

.stock-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.stock-badge.in-stock {
    background-color: #D4EDDA;
    color: #155724;
}

.stock-badge.out-of-stock {
    background-color: #F8D7DA;
    color: #721C24;
}

.featured-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.featured-badge.featured {
    background-color: #CCE5FF;
    color: #004085;
}

.featured-badge.not-featured {
    background-color: #F0F0F0;
    color: #666;
}

.admin-form-wrapper {
    background-color: var(--bg-white);
    padding: 30px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.admin-form {
    max-width: 800px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: var(--text-dark);
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
}

.form-group textarea {
    resize: vertical;
}

.form-group.checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0;
}

.form-group.checkbox input {
    width: auto;
    margin: 0;
}

.form-group.checkbox label {
    margin-bottom: 0;
    font-weight: normal;
}

.image-preview {
    max-width: 200px;
    max-height: 200px;
    margin-top: 10px;
    border-radius: 4px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.form-actions .btn {
    flex: 1;
    max-width: 200px;
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .admin-table {
        font-size: 12px;
    }

    .admin-table th,
    .admin-table td {
        padding: 8px;
    }
}
</style>

<?php include '../footer.php'; ?>
