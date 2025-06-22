<?php
session_start();
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/header.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied. Admins only.</div>";
    include '../includes/footer.php';
    exit;
}

// Fetch summary stats
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$pending_orders = $conn->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'Pending'")->fetch_assoc()['count'];

// Fetch recent admin logs
$logs = $conn->query("
    SELECT l.action, l.target, l.created_at, u.username 
    FROM admin_logs l 
    JOIN users u ON l.admin_id = u.id 
    ORDER BY l.created_at DESC 
    LIMIT 5
");
?>

<h3 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h3>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-box-seam"></i> Products</h6>
                <h3><?= $total_products ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-receipt"></i> Orders</h6>
                <h3><?= $total_orders ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-hourglass-split"></i> Pending Orders</h6>
                <h3><?= $pending_orders ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-dark shadow">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-people"></i> Users</h6>
                <h3><?= $total_users ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Action Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><i class="bi bi-plus-square"></i> Add Product</h5>
                <p class="card-text">Upload new accessories.</p>
                <a href="../products/add.php" class="btn btn-primary mt-auto">
                    <i class="bi bi-plus-lg"></i> Add
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><i class="bi bi-box-seam"></i> Manage Products</h5>
                <p class="card-text">Edit or delete listings.</p>
                <a href="../products/view.php" class="btn btn-info text-white mt-auto">
                    <i class="bi bi-pencil-square"></i> View
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-dark shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><i class="bi bi-people-fill"></i> Manage Users</h5>
                <p class="card-text">View and control users.</p>
                <a href="users.php" class="btn btn-dark mt-auto">
                    <i class="bi bi-eye"></i> View
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><i class="bi bi-receipt-cutoff"></i> Orders</h5>
                <p class="card-text">Track & manage orders.</p>
                <a href="orders.php" class="btn btn-success mt-auto">
                    <i class="bi bi-eye"></i> View
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Admin Activity Logs -->
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-secondary text-white">
        <i class="bi bi-clipboard-data"></i> Recent Admin Activity
    </div>
    <div class="card-body p-0">
        <?php if ($logs->num_rows === 0): ?>
            <p class="p-3 text-muted">No activity logs found.</p>
        <?php else: ?>
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($log = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><i class="bi bi-person-badge"></i> <?= htmlspecialchars($log['username']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['target'] ?? '-') ?></td>
                        <td><small class="text-muted"><?= date("d M Y, H:i", strtotime($log['created_at'])) ?></small></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="card-footer text-end">
        <a href="logs.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-box-arrow-up-right"></i> View Full Log
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
