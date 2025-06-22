<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nyali Car Deco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode .navbar {
            background-color: #1f1f1f !important;
        }
        body.dark-mode .card {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        body.dark-mode .bg-light {
            background-color: #2c2c2c !important;
        }
        body.dark-mode .text-primary {
            color: #90caf9 !important;
        }
        body.dark-mode .btn-outline-secondary {
            border-color: #bbb;
            color: #ccc;
        }
    </style>
</head>
<body>

<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/nyali_car_deco/index.php">
            <i class="bi bi-car-front-fill"></i> Nyali Car Deco
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user'])): ?>

                    <?php if ($_SESSION['user']['role'] !== 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/nyali_car_deco/cart/view.php">
                                <i class="bi bi-cart4"></i> Cart
                                <?php if ($cart_count > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?= $cart_count ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/nyali_car_deco/wishlist/view.php"><i class="bi bi-heart-fill"></i> Wishlist</a></li>
                        <li class="nav-item"><a class="nav-link" href="/nyali_car_deco/orders/history.php"><i class="bi bi-clock-history"></i> My Orders</a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/nyali_car_deco/admin/dashboard.php"><i class="bi bi-speedometer2"></i> Admin</a></li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (!empty($_SESSION['user']['profile_photo'])): ?>
                                <img src="/nyali_car_deco/uploads/avatars/<?= htmlspecialchars($_SESSION['user']['profile_photo']) ?>" width="30" height="30" class="rounded-circle me-2" alt="Profile">
                            <?php endif; ?>
                            <?= htmlspecialchars($_SESSION['user']['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/nyali_car_deco/profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="/nyali_car_deco/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/nyali_car_deco/register.php"><i class="bi bi-person-plus"></i> Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="/nyali_car_deco/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                <?php endif; ?>

                <li class="nav-item ms-2">
                    <button id="darkToggle" class="btn btn-sm btn-outline-light" title="Toggle dark mode">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
