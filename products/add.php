<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied</div>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = $_POST['price'];

    $imgName = null;
    if ($_FILES['image']['name']) {
        $imgName = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../uploads/" . $imgName);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $desc, $price, $imgName);
    $stmt->execute();

    echo "<div class='alert alert-success'>Product added successfully!</div>";
}
?>

<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>Price (KES)</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Product Image</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button class="btn btn-primary">Add Product</button>
</form>

<?php include '../includes/footer.php'; ?>
