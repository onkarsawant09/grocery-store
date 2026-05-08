<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

// Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $stmt = $conn->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $image);
    $stmt->execute();
    header("Location: categories.php");
    exit;
}

// Delete Category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    header("Location: categories.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .form-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Manage Categories</h1>
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
        <div class="form-container">
            <h2>Add New Category</h2>
            <form method="post">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Image URL (e.g., image/cat-1.png)</label>
                    <input type="text" name="image" required>
                </div>
                <button type="submit" name="add_category" class="admin-btn">Add Category</button>
            </form>
        </div>

        <h2>Existing Categories</h2>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Image</th><th>Name</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while($cat = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><img src="../<?= $cat['image'] ?>" width="50"></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td><a href="?delete=<?= $cat['id'] ?>" class="admin-btn-danger admin-btn-sm" onclick="return confirm('Delete this category?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>