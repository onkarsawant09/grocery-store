<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

// Add Blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_blog'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'];
    $author = $_POST['author'];
    $stmt = $conn->prepare("INSERT INTO blogs (title, content, image, author) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $image, $author);
    $stmt->execute();
    header("Location: blogs.php");
    exit;
}

// Delete Blog
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM blogs WHERE id = $id");
    header("Location: blogs.php");
    exit;
}

$blogs = $conn->query("SELECT * FROM blogs ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .form-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Manage Blogs</h1>
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
            <h2>Add New Blog Post</h2>
            <form method="post">
                <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Content (full article)</label><textarea name="content" rows="5" required></textarea></div>
                <div class="form-group"><label>Image URL</label><input type="text" name="image" placeholder="e.g., image/blog-1.jpg" required></div>
                <div class="form-group"><label>Author</label><input type="text" name="author" value="Admin" required></div>
                <button type="submit" name="add_blog" class="admin-btn">Add Blog</button>
            </form>
        </div>

        <h2>Existing Blogs</h2>
        <table class="admin-table">
            <thead><tr><th>ID</th><th>Image</th><th>Title</th><th>Author</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php while($blog = $blogs->fetch_assoc()): ?>
                <tr>
                    <td><?= $blog['id'] ?></td>
                    <td><img src="../<?= $blog['image'] ?>" width="50"></td>
                    <td><?= htmlspecialchars($blog['title']) ?></td>
                    <td><?= htmlspecialchars($blog['author']) ?></td>
                    <td><?= date('d M Y', strtotime($blog['created_at'])) ?></td>
                    <td><a href="edit-blog.php?id=<?= $blog['id'] ?>" class="admin-btn-sm">Edit</a> <a href="?delete=<?= $blog['id'] ?>" class="admin-btn-danger admin-btn-sm" onclick="return confirm('Delete this blog?')">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>