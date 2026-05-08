<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';
$total_orders = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as s FROM orders")->fetch_assoc()['s'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>Grocery Store Admin</h1>
        <div class="admin-nav">
            <a href="products.php">Products</a>
            <a href="categories.php">Categories</a>
            <a href="orders.php">Orders</a>
            <a href="blogs.php">Blogs</a>
            <a href="reviews.php">Reviews</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="admin-container">
        <div class="stats-grid">
            <div class="stat-card"><h3>Orders</h3><div class="stat-number"><?= $total_orders ?></div></div>
            <div class="stat-card"><h3>Products</h3><div class="stat-number"><?= $total_products ?></div></div>
            <div class="stat-card"><h3>Users</h3><div class="stat-number"><?= $total_users ?></div></div>
            <div class="stat-card"><h3>Revenue</h3><div class="stat-number">₹<?= number_format($total_revenue ?? 0, 2) ?></div></div>
        </div>
    </div>
</body>
</html>