<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /nyali_car_deco/login.php");
    exit;
}
include '../includes/config.php';

$id = $_GET['id'] ?? null;
if (!$id) die('No product selected.');

// Get product from DB
$result = $conn->query("SELECT * FROM products WHERE id = $id LIMIT 1");
$product = $result->fetch_assoc();

if (!$product) die('Product not found.');

// Init cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update cart item
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1
    ];
}

header("Location: view.php");
exit;
