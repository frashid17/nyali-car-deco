<?php include '../includes/config.php'; ?>
<?php include '../includes/header.php'; ?>

<h2 class="mb-4"><i class="bi bi-shop-window"></i> Available Car Accessories</h2>

<h2 class="mb-4"><i class="bi bi-search"></i> Search Car Accessories</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" class="form-control" placeholder="Search by name or description" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    </div>
    <div class="col-auto">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        <a href="view.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
    </div>
</form>

<div class="row">
    <?php
    $q = $_GET['q'] ?? '';
    $q = trim($q);

    if ($q !== '') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
        $like = "%$q%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
    }

    while ($product = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php if ($product['image']): ?>
                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="p-5 bg-light text-center text-muted">
                        <i class="bi bi-image-alt"></i> No Image
                    </div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><i class="bi bi-tag-fill"></i> <?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text"><i class="bi bi-card-text"></i> <?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                    <p class="text-primary"><i class="bi bi-currency-exchange"></i> KES <?= number_format($product['price'], 2) ?></p>

                    <div class="mt-auto">
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-warning btn-sm mb-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin'): ?>
                            <div class="d-flex justify-content-between">
                                <a href="../cart/add.php?id=<?= $product['id'] ?>" class="btn btn-primary">
                                    <i class="bi bi-cart-plus-fill"></i> Add to Cart
                                </a>
                                <a href="../wishlist/add.php?id=<?= $product['id'] ?>" class="btn btn-outline-danger">
                                    <i class="bi bi-heart"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <a href="/nyali_car_deco/login.php" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-lock-fill"></i> Login to Add
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
