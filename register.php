<?php
include 'includes/config.php';
include 'includes/header.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Email or username already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed.";
        }
    }
}
?>

<div class="card mx-auto shadow" style="max-width: 500px;">
    <div class="card-body">
        <h3 class="card-title text-center mb-4"><i class="bi bi-person-plus"></i> Create an Account</h3>
        <form method="POST">
            <div class="mb-3">
                <label><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label><i class="bi bi-envelope-at"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label><i class="bi bi-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Register</button>
        </form>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
