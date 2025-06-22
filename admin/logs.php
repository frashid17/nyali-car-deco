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

// Fetch logs with admin usernames
$query = "
    SELECT l.id, l.action, l.target, l.created_at, u.username
    FROM admin_logs l
    JOIN users u ON l.admin_id = u.id
    ORDER BY l.created_at DESC
";
$result = $conn->query($query);
?>

<h3><i class="bi bi-clipboard-data"></i> Admin Activity Log</h3>
<hr>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No log entries yet.</div>
<?php else: ?>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Admin</th>
            <th>Action</th>
            <th>Target</th>
            <th>Timestamp</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($log = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $log['id'] ?></td>
                <td><i class="bi bi-person-fill"></i> <?= htmlspecialchars($log['username']) ?></td>
                <td><?= htmlspecialchars($log['action']) ?></td>
                <td><?= htmlspecialchars($log['target']) ?: '<em class="text-muted">N/A</em>' ?></td>
                <td><span class="text-muted"><?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?></span></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
