<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Users';
$action = $_GET['action'] ?? 'list';
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle user operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? '';

    if ($action_type === 'edit' && $user_id > 0) {
        $full_name = sanitize($_POST['full_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $role = sanitize($_POST['role'] ?? 'customer');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (!in_array($role, ['customer', 'admin'])) {
            $role = 'customer';
        }

        $conn->query("UPDATE users SET full_name='$full_name', email='$email', role='$role', is_active=$is_active WHERE id=$user_id");
        $success = 'User updated successfully!';
        $action = 'list';
    } elseif ($action_type === 'delete' && $user_id > 0) {
        $conn->query("DELETE FROM users WHERE id = $user_id");
        $success = 'User deleted successfully!';
        $action = 'list';
    }
}

include '../header.php';
?>

<section class="section">
    <div class="container">
        <div class="admin-header">
            <h1>User Management</h1>
            <?php if ($action !== 'list'): ?>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

                        if ($users->num_rows > 0):
                            while ($user = $users->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?');">
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- Edit Form -->
        <?php elseif ($action === 'edit' && $user_id > 0): ?>
            <?php
            $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
            if (!$user) {
                echo '<p>User not found</p>';
            } else {
            ?>

            <div class="admin-form-wrapper">
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action_type" value="edit">

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" disabled
                               value="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="customer" <?php echo $user['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="is_active">Active Status</label>
                            <select id="is_active" name="is_active">
                                <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="user-info-box">
                        <h3>User Information</h3>
                        <div class="info-row">
                            <span>Phone:</span>
                            <strong><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Address:</span>
                            <strong><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>City/State:</span>
                            <strong><?php echo htmlspecialchars(($user['city'] ?? '') . ', ' . ($user['state'] ?? '')); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Registered:</span>
                            <strong><?php echo date('F d, Y H:i', strtotime($user['created_at'])); ?></strong>
                        </div>
                        <div class="info-row">
                            <span>Last Updated:</span>
                            <strong><?php echo date('F d, Y H:i', strtotime($user['updated_at'])); ?></strong>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">Update User</button>
                        <a href="?action=list" class="btn btn-outline btn-lg">Cancel</a>
                    </div>
                </form>
            </div>

            <?php } ?>
        <?php endif; ?>
    </div>
</section>

<style>
.role-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    color: white;
}

.role-badge.admin {
    background-color: var(--primary-color);
}

.role-badge.customer {
    background-color: var(--secondary-color);
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    color: white;
}

.status-badge.active {
    background-color: var(--success-color);
}

.status-badge.inactive {
    background-color: var(--text-light);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.user-info-box {
    background-color: var(--bg-light);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.user-info-box h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-row span {
    color: var(--text-light);
}

.info-row strong {
    color: var(--text-dark);
}
</style>

<?php include '../footer.php'; ?>
