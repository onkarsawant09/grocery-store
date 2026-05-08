<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$rating = (int)$_POST['rating'];
$comment = $_POST['comment'];
$stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
$stmt->execute();
header("Location: product.php?id=$product_id");
exit;
?>