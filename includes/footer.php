</div> <!-- Close .container -->

<!-- Sticky Bottom Nav (Mobile Only) -->
<!-- Sticky Bottom Nav (Mobile Only) -->
<nav class="navbar navbar-dark bg-dark navbar-expand d-md-none fixed-bottom border-top">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a class="nav-link text-center" href="/nyali_car_deco/index.php">
                <i class="bi bi-house-door-fill fs-5"></i><br><small>Home</small>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center position-relative" href="/nyali_car_deco/cart/view.php">
                <i class="bi bi-cart4 fs-5"></i>
                <?php if ($cart_count > 0): ?>
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-danger">
                        <?= $cart_count ?>
                    </span>
                <?php endif; ?>
                <br><small>Cart</small>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" href="/nyali_car_deco/wishlist/view.php">
                <i class="bi bi-heart-fill fs-5"></i><br><small>Wishlist</small>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" href="/nyali_car_deco/orders/history.php">
                <i class="bi bi-box2 fs-5"></i><br><small>Orders</small>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center" href="/nyali_car_deco/profile.php">
                <i class="bi bi-person-circle fs-5"></i><br><small>Me</small>
            </a>
        </li>
    </ul>
</nav>


<!-- Bootstrap Bundle JS (for navbar toggle) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const toggleBtn = document.getElementById('darkToggle');
    const isDark = localStorage.getItem('darkMode') === 'enabled';

    if (isDark) {
        document.body.classList.add('dark-mode');
    }

    toggleBtn?.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const enabled = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', enabled ? 'enabled' : 'disabled');
    });
</script>

</body>
</html>
