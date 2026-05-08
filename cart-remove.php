<?php
session_start();
$id = $_GET['id'] ?? '';
if ($id && isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($id) {
        return $item['id'] != $id;
    });
}
header('Location: cart.php');
exit;
?>