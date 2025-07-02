<?php
// ⚠️ Replace these values and rename to config.php

$host = 'localhost';
$db   = 'your_database_name';
$user = 'your_mysql_user';
$pass = 'your_mysql_password';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
