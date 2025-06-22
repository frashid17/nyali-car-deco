<?php
session_start();
include 'includes/config.php';
include 'includes/header.php';

// If cart is empty
if (empty($_SESSION['cart'])) {
    echo "<div class='alert alert-warning'>Your cart is empty. <a href='products/view.php'>Go shopping</a></div>";
    include 'includes/footer.php';
    exit;
}

$grandTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $grandTotal += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    $user_id = $_SESSION['user']['id'];
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, email, address, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $user_id, $name, $email, $address, $grandTotal);
    $stmt->execute();

    // Clear cart
    $_SESSION['cart'] = [];

    echo "<div class='alert alert-success'><i class='bi bi-check-circle-fill'></i> Thank you! Your order has been placed.</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="card mx-auto shadow" style="max-width: 600px;">
    <div class="card-body">
        <h3 class="card-title mb-4"><i class="bi bi-credit-card"></i> Checkout</h3>

        <form method="POST">
            <div class="mb-3">
                <label><i class="bi bi-person"></i> Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label><i class="bi bi-geo-alt"></i> Shipping Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>

            <h4><i class="bi bi-cash-stack"></i> Total: <span class="text-primary">KES <?= number_format($grandTotal, 2) ?></span></h4>

            <button class="btn btn-success w-100 mt-3">
                <i class="bi bi-cart-check-fill"></i> Place Order
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
