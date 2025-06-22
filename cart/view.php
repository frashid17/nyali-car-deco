<?php include '../includes/header.php'; ?>

<h2>Your Shopping Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Your cart is empty.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price (KES)</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $grandTotal = 0;
        foreach ($_SESSION['cart'] as $id => $item):
            $total = $item['price'] * $item['quantity'];
            $grandTotal += $total;
            ?>
            <tr>
                <td><img src="../uploads/<?= $item['image'] ?>" width="80"></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($total, 2) ?></td>
                <td><a href="remove.php?id=<?= $id ?>" class="btn btn-sm btn-danger">Remove</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h4>Total: KES <?= number_format($grandTotal, 2) ?></h4>
    <a href="/nyali_car_deco/checkout.php" class="btn btn-success">Proceed to Checkout</a>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
