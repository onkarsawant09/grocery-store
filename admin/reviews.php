<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

// Delete review
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM reviews WHERE id = $id");
    header("Location: reviews.php");
    exit;
}

$reviews = $conn->query("SELECT r.*, u.name as user_name, p.name as product_name FROM reviews r LEFT JOIN users u ON r.user_id=u.id LEFT JOIN products p ON r.product_id=p.id ORDER BY r.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>Manage Reviews</h1>
        <div class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="categories.php">Categories</a>
            <a href="orders.php">Orders</a>
            <a href="blogs.php">Blogs</a>
            <a href="reviews.php">Reviews</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="admin-container">
        <h2>Customer Reviews</h2>
        <table class="admin-table">
            <thead><tr><th>ID</th><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php while($rev = $reviews->fetch_assoc()): ?>
                <tr>
                    <td><?= $rev['id'] ?></td>
                    <td><?= htmlspecialchars($rev['product_name']) ?></td>
                    <td><?= htmlspecialchars($rev['user_name'] ?? 'Anonymous') ?></td>
                    <td><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']) ?></td>
                    <td><?= htmlspecialchars(substr($rev['comment'],0,80)) ?>...</td>
                    <td><?= date('d M Y', strtotime($rev['created_at'])) ?></td>
                    <td><a href="?delete=<?= $rev['id'] ?>" class="admin-btn-danger admin-btn-sm" onclick="return confirm('Delete this review?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>