<?php
$host = 'localhost';
$db = 'nyali_db';
$user = 'root';
$pass = 'Mehrunissa@2012';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
