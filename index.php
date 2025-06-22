<?php
include 'includes/config.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-dark text-white py-5 shadow-sm">
    <div class="container text-center">
        <h1 class="display-4"><i class="bi bi-car-front-fill"></i> Nyali Car Deco</h1>
        <p class="lead mt-3 mb-4">Top-quality car accessories delivered to your doorstep across Kenya.</p>
        <a href="products/view.php" class="btn btn-outline-light btn-lg">
            <i class="bi bi-shop"></i> Browse Products
        </a>
    </div>
</section>

<!-- About Us Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="mb-3"><i class="bi bi-info-circle"></i> About Us</h3>
                <p>
                    Nyali Car Deco offers a curated selection of stylish, affordable, and durable car accessories.
                    Whether you’re upgrading your car’s interior, boosting your sound system, or adding personal style — we’ve got you covered.
                </p>
                <ul class="list-unstyled mt-3">
                    <li><i class="bi bi-check2-circle text-success"></i> Interior & exterior accessories</li>
                    <li><i class="bi bi-check2-circle text-success"></i> Competitive prices</li>
                    <li><i class="bi bi-check2-circle text-success"></i> Fast nationwide delivery</li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <img src="assets/about.jpg" class="img-fluid rounded shadow-sm" alt="About Nyali Car Deco">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php $result = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 3"); ?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-stars"></i> Featured Accessories</h3>
            <a href="products/view.php" class="btn btn-sm btn-outline-primary">View All</a>
        </div>

        <div class="row">
            <?php while ($p = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <?php if ($p['image']): ?>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                        <?php else: ?>
                            <div class="p-5 text-center text-muted"><i class="bi bi-image fs-1"></i><br>No Image</div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                            <p class="text-muted small"><?= htmlspecialchars(substr($p['description'], 0, 80)) ?>...</p>
                            <p class="fw-bold text-primary mb-3">KES <?= number_format($p['price'], 2) ?></p>

                            <div class="mt-auto">
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin'): ?>
                                    <div class="d-flex justify-content-between">
                                        <a href="cart/add.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-success">
                                            <i class="bi bi-cart-plus"></i> Add to Cart
                                        </a>
                                        <a href="wishlist/add.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-heart"></i>
                                        </a>
                                    </div>
                                <?php elseif (!isset($_SESSION['user'])): ?>
                                    <a href="login.php" class="btn btn-sm btn-outline-secondary w-100">
                                        <i class="bi bi-lock-fill"></i> Login to Add
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <h3 class="mb-4"><i class="bi bi-telephone-fill"></i> Contact Us</h3>
        <div class="row">
            <div class="col-md-6">
                <p><i class="bi bi-envelope"></i> Email: <a href="mailto:info@nyalicar.com">info@nyalicar.com</a></p>
                <p><i class="bi bi-phone"></i> Phone: <a href="tel:+254712345678">+254 712 345 678</a></p>
                <p><i class="bi bi-geo-alt"></i> Address: Nyali, Mombasa, Kenya</p>
            </div>
            <div class="col-md-6">
                <iframe src="https://maps.google.com/maps?q=nyali%20mombasa&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="200" frameborder="0" style="border:0;" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
