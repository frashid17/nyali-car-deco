<?php
include 'includes/config.php';
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-danger'>You must be logged in to view this page.</div>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$success = '';
$errors = [];

// === HANDLE PROFILE UPDATE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        // Check if username/email already exists (excluding current user)
        $check = $conn->prepare("SELECT * FROM users WHERE (email = ? OR username = ?) AND id != ?");
        $check->bind_param("ssi", $email, $username, $user_id);
        $check->execute();
        $exists = $check->get_result();
        if ($exists->num_rows > 0) {
            $errors[] = "Email or username already in use.";
        } else {
            $update = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $update->bind_param("ssi", $username, $email, $user_id);
            $update->execute();
            $success = "Profile updated.";
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
        }
    }

    // === HANDLE PASSWORD CHANGE ===
    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];

        if (!password_verify($current, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            $update->execute();
            $success = "Password changed.";
        }
    }

    // === HANDLE PROFILE PHOTO UPLOAD ===
    if (isset($_POST['upload_photo']) && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $filename = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetPath = "uploads/avatars/" . $filename;
        if (!is_dir("uploads/avatars")) mkdir("uploads/avatars", 0777, true);

        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath);
        $update = $conn->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
        $update->bind_param("si", $filename, $user_id);
        $update->execute();
        $success = "Profile photo updated.";
        $_SESSION['user']['profile_photo'] = $filename;
    }
}
?>

<div class="container mb-5">
    <h3 class="mb-4"><i class="bi bi-person-circle"></i> Profile Settings</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <ul class="nav nav-tabs mb-4" id="profileTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#info">Profile Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#password">Change Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#photo">Profile Photo</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Info Tab -->
        <div class="tab-pane fade show active" id="info">
            <form method="POST">
                <input type="hidden" name="update_profile" value="1">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <!-- Password Tab -->
        <div class="tab-pane fade" id="password">
            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <button class="btn btn-warning">Change Password</button>
            </form>
        </div>

        <!-- Photo Tab -->
        <div class="tab-pane fade" id="photo">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_photo" value="1">
                <div class="mb-3">
                    <label>Current Photo:</label><br>
                    <?php if ($user['profile_photo']): ?>
                        <img src="uploads/avatars/<?= htmlspecialchars($user['profile_photo']) ?>" width="120" class="rounded-circle mb-3">
                    <?php else: ?>
                        <p class="text-muted">No photo uploaded.</p>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label>Upload New Photo</label>
                    <input type="file" name="photo" class="form-control" required>
                </div>
                <button class="btn btn-secondary">Upload</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
