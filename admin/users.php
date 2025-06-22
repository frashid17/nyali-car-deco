<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';

// Access control
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied.</div>";
    include '../includes/footer.php';
    exit;
}

// Handle actions: change role, delete, toggle active
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = (int)$_POST['user_id'];

    if ($action === 'delete') {
        $conn->query("DELETE FROM users WHERE id = $user_id AND id != {$_SESSION['user']['id']}");
    } elseif ($action === 'toggle') {
        $conn->query("UPDATE users SET is_active = NOT is_active WHERE id = $user_id AND id != {$_SESSION['user']['id']}");
    } elseif ($action === 'role') {
        $new_role = $_POST['new_role'] === 'admin' ? 'admin' : 'customer';
        $conn->query("UPDATE users SET role = '$new_role' WHERE id = $user_id AND id != {$_SESSION['user']['id']}");
    }
}

// Handle search
$search = trim($_GET['q'] ?? '');
$query = "SELECT * FROM users";
if ($search !== '') {
    $query .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
?>

<h3><i class="bi bi-people-fill"></i> Manage Users</h3>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" class="form-control" placeholder="Search by username or email" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        <a href="users.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
    </div>
</form>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No users found.</div>
<?php else: ?>
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>ID</th>
            <th><i class="bi bi-person"></i> Username</th>
            <th><i class="bi bi-envelope"></i> Email</th>
            <th><i class="bi bi-shield-check"></i> Role</th>
            <th><i class="bi bi-toggle-on"></i> Status</th>
            <th><i class="bi bi-calendar"></i> Created</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                        <?= $user['is_active'] ? 'Active' : 'Disabled' ?>
                    </span>
                </td>
                <td><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                        <!-- Role Switch -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="action" value="role">
                            <input type="hidden" name="new_role" value="<?= $user['role'] === 'admin' ? 'customer' : 'admin' ?>">
                            <button class="btn btn-sm btn-outline-warning" title="Change Role">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </form>

                        <!-- Toggle Active -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="action" value="toggle">
                            <button class="btn btn-sm btn-outline-info" title="Enable/Disable">
                                <i class="bi bi-toggle-<?= $user['is_active'] ? 'on' : 'off' ?>"></i>
                            </button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn btn-sm btn-outline-danger" title="Delete User">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">You</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
