<?php
function log_admin_action($admin_id, $action, $target = null) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO admin_logs (admin_id, action, target) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $admin_id, $action, $target);
    $stmt->execute();
}
