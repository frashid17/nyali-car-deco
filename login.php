<?php
include 'includes/config.php';
include 'includes/header.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // 🟢 Log admin login
            if ($user['role'] === 'admin') {
                log_admin_action($user['id'], "Logged in");
            }

            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "User not found.";
    }
}
?>

<div class="card mx-auto shadow" style="max-width: 500px;">
    <div class="card-body">
        <h3 class="card-title text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> Login</h3>
        <form method="POST">
            <div class="mb-3">
                <label><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label><i class="bi bi-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
        </form>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
