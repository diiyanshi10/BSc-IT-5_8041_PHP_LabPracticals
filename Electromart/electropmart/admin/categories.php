<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Categories';
$action = $_GET['action'] ?? 'list';
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle category operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? '';

    if ($action_type === 'add' || $action_type === 'edit') {
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $image_url = sanitize($_POST['image_url'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name)) {
            $error = 'Category name is required.';
        } else {
            if ($action_type === 'add') {
                $slug = strtolower(str_replace(' ', '-', $name));
                $conn->query("INSERT INTO categories (name, slug, description, image_url, is_active) 
                             VALUES ('$name', '$slug', '$description', '$image_url', $is_active)");
                $success = 'Category added successfully!';
                $action = 'list';
            } elseif ($action_type === 'edit' && $category_id > 0) {
                $conn->query("UPDATE categories SET name='$name', description='$description', 
                             image_url='$image_url', is_active=$is_active WHERE id=$category_id");
                $success = 'Category updated successfully!';
                $action = 'list';
            }
        }
    } elseif ($action_type === 'delete' && $category_id > 0) {
        $conn->query("DELETE FROM categories WHERE id = $category_id");
        $success = 'Category deleted successfully!';
        $action = 'list';
    }
}

include '../header.php';
?>

<section class="section">
    <div class="container">
        <div class="admin-header">
            <h1>Category Management</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="btn btn-primary">+ Add Category</a>
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
            <div class="categories-grid">
                <?php
                $categories = $conn->query("SELECT * FROM categories ORDER BY name");

                if ($categories->num_rows > 0):
                    while ($category = $categories->fetch_assoc()):
                        $product_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE category_id = " . $category['id'])->fetch_assoc()['count'];
                ?>
                    <div class="category-card">
                        <div class="category-image">
                            <?php if ($category['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="">
                            <?php else: ?>
                                <div class="no-image"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="category-details">
                            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                            <p><?php echo $product_count; ?> products</p>
                            <div class="category-status">
                                <span class="status-badge <?php echo $category['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $category['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="category-actions">
                            <a href="?action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                                <input type="hidden" name="action_type" value="delete">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php
                    endwhile;
                else:
                ?>
                    <p style="grid-column: 1/-1; text-align: center; padding: 40px;">No categories found</p>
                <?php endif; ?>
            </div>

        <!-- Add/Edit Form -->
        <?php else: ?>
            <div class="admin-form-wrapper">
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>">
                    
                    <?php
                    if ($action === 'edit' && $category_id > 0) {
                        $category = $conn->query("SELECT * FROM categories WHERE id = $category_id")->fetch_assoc();
                        if (!$category) redirect('categories.php');
                    }
                    ?>

                    <div class="form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($category) ? htmlspecialchars($category['name']) : ''; ?>"
                               placeholder="Enter category name">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" 
                                  placeholder="Enter category description"><?php echo isset($category) ? htmlspecialchars($category['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input type="url" id="image_url" name="image_url" 
                               value="<?php echo isset($category) ? htmlspecialchars($category['image_url']) : ''; ?>"
                               placeholder="https://example.com/image.jpg">
                        <?php if (isset($category) && $category['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="" class="image-preview">
                        <?php endif; ?>
                    </div>

                    <div class="form-group checkbox">
                        <input type="checkbox" id="is_active" name="is_active" 
                               <?php echo !isset($category) || $category['is_active'] ? 'checked' : ''; ?>>
                        <label for="is_active">Active</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?php echo $action === 'edit' ? 'Update Category' : 'Add Category'; ?>
                        </button>
                        <a href="?action=list" class="btn btn-outline btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.category-card {
    background-color: var(--bg-white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.category-image {
    width: 100%;
    height: 150px;
    background-color: var(--bg-light);
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--border-color);
}

.category-details {
    padding: 15px;
    flex: 1;
}

.category-details h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: var(--text-dark);
}

.category-details p {
    margin: 5px 0 10px 0;
    color: var(--text-light);
    font-size: 12px;
}

.category-status {
    margin: 10px 0;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    color: white;
}

.status-badge.active {
    background-color: var(--success-color);
}

.status-badge.inactive {
    background-color: var(--text-light);
}

.category-actions {
    padding: 10px 15px;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 8px;
}

.category-actions .btn {
    flex: 1;
    font-size: 11px;
    padding: 6px 10px;
}

.image-preview {
    max-width: 200px;
    max-height: 150px;
    margin-top: 10px;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../footer.php'; ?>
