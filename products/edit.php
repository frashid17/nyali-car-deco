<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';
include '../includes/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied.</div>";
    include '../includes/footer.php';
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='alert alert-danger'>Product ID missing.</div>";
    include '../includes/footer.php';
    exit;
}


// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    // Keep current image by default
    $image = $product['image'];

    // If a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    // Update product details
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $id);
    $stmt->execute();

    echo "<div class='alert alert-success'>Product updated successfully.</div>";

    // Refresh the updated data
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}
// ... after successful update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $name, $desc, $price, $id);
    $stmt->execute();

    // ✅ Log admin action
    if ($_SESSION['user']['role'] === 'admin') {
        log_admin_action($_SESSION['user']['id'], "Edited product", $name);
    }

    echo "<div class='alert alert-success'>Product updated successfully.</div>";
}

?>

<h3><i class="bi bi-pencil-square"></i> Edit Product</h3>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <div class="mb-3">
        <label>Price (KES)</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" step="0.01" required>
    </div>
    <div class="mb-3">
        <label>Current Image:</label><br>
        <?php if ($product['image']): ?>
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" width="150">
        <?php else: ?>
            <p class="text-muted">No image available.</p>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label>Change Image (optional)</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-success"><i class="bi bi-save"></i> Save Changes</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>
