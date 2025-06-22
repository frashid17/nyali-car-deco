<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';
include '../includes/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied. Admins only.</div>";
    include '../includes/footer.php';
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    // ✅ Log the update
    if (isset($_SESSION['user']['id'])) {
        log_admin_action($_SESSION['user']['id'], "Updated order status", "Order #$order_id to $status");
    }
}

// Fetch all orders
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<h3><i class="bi bi-receipt-cutoff"></i> Manage Orders</h3>
<hr>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No orders have been placed yet.</div>
<?php else: ?>
    <table class="table table-bordered table-hover align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Address</th>
            <th>Total (KES)</th>
            <th>Change Status</th>
            <th>Date</th>
            <th>Current Status</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td><?= htmlspecialchars($order['email']) ?></td>
                <td><?= htmlspecialchars($order['address']) ?></td>
                <td><?= number_format($order['total'], 2) ?></td>
                <td>
                    <form method="POST" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status" class="form-select form-select-sm">
                            <?php
                            $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
                            foreach ($statuses as $statusOption): ?>
                                <option value="<?= $statusOption ?>" <?= $order['status'] === $statusOption ? 'selected' : '' ?>>
                                    <?= $statusOption ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>
                </td>
                <td><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($order['status']) ?></span></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
