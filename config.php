<?php
session_start();
$host = '127.0.0.1';   // Use IP instead of localhost to force TCP
$user = 'root';
$pass = 'root1234';
$dbname = 'grocery_store';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>