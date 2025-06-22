<?php
include '../includes/config.php';
include '../includes/functions.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$id = $_GET['id'] ?? null;
if ($id) {
    // Get product name for logging
    $stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    $conn->query("DELETE FROM products WHERE id = $id");

    // ✅ Log deletion
    log_admin_action($_SESSION['user']['id'], "Deleted product", $product['name']);

    header("Location: view.php");
    exit;
}
?>
