<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = $_POST['id'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);

foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id) {
        if ($quantity > 0) {
            $item['quantity'] = $quantity;
        } else {
            // remove if quantity 0
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($i) use ($id) {
                return $i['id'] != $id;
            });
        }
        break;
    }
}
header('Location: cart.php');
exit;
?>