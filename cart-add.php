<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$image = $_POST['image'] ?? '';

if ($id && $name && $price) {
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'quantity' => 1
        ];
    }
    $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['status' => 'success', 'cart_count' => $cart_count]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing product data']);
}
?>