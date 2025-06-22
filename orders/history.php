<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-danger'>Please log in to view your order history.</div>";
    include '../includes/footer.php';
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3><i class="bi bi-clock-history"></i> My Order History</h3>
<hr>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">You haven't placed any orders yet.</div>
<?php else: ?>
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Order #</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Total (KES)</th>
            <th>Status</th>
            <th>Date</th>
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
                    <span class="badge bg-<?=
                    $order['status'] === 'Pending' ? 'warning' :
                        ($order['status'] === 'Processing' ? 'info' :
                            ($order['status'] === 'Shipped' ? 'primary' :
                                ($order['status'] === 'Delivered' ? 'success' : 'secondary')))
                    ?>">
                        <?= htmlspecialchars($order['status']) ?>
                    </span>
                </td>
                <td><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>


<?php endif; ?>

<?php include '../includes/footer.php'; ?>
