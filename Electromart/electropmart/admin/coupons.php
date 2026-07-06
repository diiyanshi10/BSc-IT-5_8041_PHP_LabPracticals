<?php
require_once '../config.php';

if (!is_admin()) {
    redirect(SITE_URL);
}

$page_title = 'Manage Coupons';
$action = $_GET['action'] ?? 'list';
$coupon_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle coupon operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_type = $_POST['action_type'] ?? '';

    if ($action_type === 'add' || $action_type === 'edit') {
        $code = strtoupper(sanitize($_POST['code'] ?? ''));
        $discount_type = sanitize($_POST['discount_type'] ?? 'percentage');
        $discount_value = floatval($_POST['discount_value'] ?? 0);
        $max_uses = intval($_POST['max_uses'] ?? 0);
        $expiry_date = sanitize($_POST['expiry_date'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($code) || $discount_value <= 0) {
            $error = 'Please fill all required fields with valid values.';
        } else {
            if ($action_type === 'add') {
                $conn->query("INSERT INTO coupons (code, discount_type, discount_value, max_uses, expiry_date, is_active) 
                             VALUES ('$code', '$discount_type', $discount_value, $max_uses, '$expiry_date', $is_active)");
                $success = 'Coupon added successfully!';
                $action = 'list';
            } elseif ($action_type === 'edit' && $coupon_id > 0) {
                $conn->query("UPDATE coupons SET code='$code', discount_type='$discount_type', discount_value=$discount_value, 
                             max_uses=$max_uses, expiry_date='$expiry_date', is_active=$is_active WHERE id=$coupon_id");
                $success = 'Coupon updated successfully!';
                $action = 'list';
            }
        }
    } elseif ($action_type === 'delete' && $coupon_id > 0) {
        $conn->query("DELETE FROM coupons WHERE id = $coupon_id");
        $success = 'Coupon deleted successfully!';
        $action = 'list';
    }
}

include '../header.php';
?>

<section class="section">
    <div class="container">
        <div class="admin-header">
            <h1>Coupon Management</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="btn btn-primary">+ Add Coupon</a>
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
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Max Uses</th>
                            <th>Used</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $coupons = $conn->query("SELECT * FROM coupons ORDER BY created_at DESC");

                        if ($coupons->num_rows > 0):
                            while ($coupon = $coupons->fetch_assoc()):
                                $is_expired = $coupon['expiry_date'] && strtotime($coupon['expiry_date']) < time();
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($coupon['code']); ?></strong>
                                </td>
                                <td><?php echo ucfirst($coupon['discount_type']); ?></td>
                                <td>
                                    <?php echo $coupon['discount_type'] === 'percentage' ? $coupon['discount_value'] . '%' : '$' . number_format($coupon['discount_value'], 2); ?>
                                </td>
                                <td><?php echo $coupon['max_uses'] > 0 ? $coupon['max_uses'] : 'Unlimited'; ?></td>
                                <td><?php echo $coupon['current_uses']; ?></td>
                                <td><?php echo $coupon['expiry_date'] ? date('M d, Y', strtotime($coupon['expiry_date'])) : 'No expiry'; ?></td>
                                <td>
                                    <?php if ($is_expired): ?>
                                        <span class="status-badge expired">Expired</span>
                                    <?php elseif ($coupon['is_active']): ?>
                                        <span class="status-badge active">Active</span>
                                    <?php else: ?>
                                        <span class="status-badge inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?action=edit&id=<?php echo $coupon['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this coupon?');">
                                        <input type="hidden" name="action_type" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $coupon['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px;">No coupons found</td>
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
                    if ($action === 'edit' && $coupon_id > 0) {
                        $coupon = $conn->query("SELECT * FROM coupons WHERE id = $coupon_id")->fetch_assoc();
                        if (!$coupon) redirect('coupons.php');
                    }
                    ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="code">Coupon Code *</label>
                            <input type="text" id="code" name="code" required 
                                   value="<?php echo isset($coupon) ? htmlspecialchars($coupon['code']) : ''; ?>"
                                   placeholder="e.g., SUMMER20" style="text-transform: uppercase;">
                        </div>

                        <div class="form-group">
                            <label for="discount_type">Discount Type *</label>
                            <select id="discount_type" name="discount_type" required>
                                <option value="percentage" <?php echo isset($coupon) && $coupon['discount_type'] === 'percentage' ? 'selected' : ''; ?>>Percentage (%)</option>
                                <option value="fixed" <?php echo isset($coupon) && $coupon['discount_type'] === 'fixed' ? 'selected' : ''; ?>>Fixed Amount ($)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="discount_value">Discount Value *</label>
                            <input type="number" id="discount_value" name="discount_value" required step="0.01" 
                                   value="<?php echo isset($coupon) ? $coupon['discount_value'] : ''; ?>"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="max_uses">Max Uses (0 = Unlimited)</label>
                            <input type="number" id="max_uses" name="max_uses" 
                                   value="<?php echo isset($coupon) ? $coupon['max_uses'] : '0'; ?>"
                                   placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" 
                                   value="<?php echo isset($coupon) && $coupon['expiry_date'] ? date('Y-m-d', strtotime($coupon['expiry_date'])) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group checkbox">
                        <input type="checkbox" id="is_active" name="is_active" 
                               <?php echo !isset($coupon) || $coupon['is_active'] ? 'checked' : ''; ?>>
                        <label for="is_active">Active</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?php echo $action === 'edit' ? 'Update Coupon' : 'Add Coupon'; ?>
                        </button>
                        <a href="?action=list" class="btn btn-outline btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.status-badge.expired {
    background-color: var(--danger-color);
}

.status-badge.active {
    background-color: var(--success-color);
}

.status-badge.inactive {
    background-color: var(--text-light);
}
</style>

<?php include '../footer.php'; ?>
