<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-danger'>Please log in to view your wishlist.</div>";
    include '../includes/footer.php';
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist = $stmt->get_result();
?>

<h3><i class="bi bi-heart-fill"></i> My Wishlist</h3>
<hr>

<?php if ($wishlist->num_rows === 0): ?>
    <div class="alert alert-info">Your wishlist is empty.</div>
<?php else: ?>
    <div class="row">
        <?php while ($item = $wishlist->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($item['image']): ?>
                        <img src="../uploads/<?= $item['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="text-primary">KES <?= number_format($item['price'], 2) ?></p>
                        <a href="../cart/add.php?id=<?= $item['id'] ?>" class="btn btn-success btn-sm mb-2">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </a>
                        <a href="remove.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-x-circle"></i> Remove
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
