<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

// Delete product (also delete image file)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Fetch image path to delete file
    $imgRes = $conn->query("SELECT image FROM products WHERE id = $id");
    if($imgRes && $row = $imgRes->fetch_assoc()) {
        $imgPath = "../" . $row['image'];
        if(file_exists($imgPath)) unlink($imgPath);
    }
    $conn->query("DELETE FROM products WHERE id = $id");
    $_SESSION['success'] = "Product deleted successfully.";
    header("Location: products.php");
    exit;
}

$products = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id ORDER BY p.id DESC");
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .add-product-btn { margin-bottom: 20px; display: inline-block; }
        .product-form { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; display: none; }
        .product-form.active { display: block; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .admin-table img { width: 50px; height: 50px; object-fit: cover; }
    </style>
    <script>
        function toggleForm() {
            document.getElementById('productForm').classList.toggle('active');
        }
    </script>
</head>
<body>
    <div class="admin-header">
        <h1>Manage Products</h1>
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
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <button class="admin-btn add-product-btn" onclick="toggleForm()">+ Add New Product</button>
        
        <div id="productForm" class="product-form">
            <h2>Add Product</h2>
            <form method="post" action="add-product.php" enctype="multipart/form-data">
                <div class="form-group"><label>Product Name</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group"><label>Price (₹)</label><input type="number" step="0.01" name="price" required></div>
                <div class="form-group"><label>Compare Price (₹)</label><input type="number" step="0.01" name="compare_price"></div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id">
                        <?php 
                        $categories->data_seek(0);
                        while($c = $categories->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product Image (JPG, PNG, GIF, WEBP)</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label>Featured Product?</label>
                    <select name="featured">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="admin-btn">Save Product</button>
            </form>
        </div>

        <h2>All Products</h2>
        <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Featured</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while($p = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><img src="../<?= htmlspecialchars($p['image']) ?>"></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['cat_name']) ?></td>
                    <td>₹<?= $p['price'] ?></td>
                    <td><?= $p['featured'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <a href="edit-product.php?id=<?= $p['id'] ?>" class="admin-btn-sm">Edit</a>
                        <a href="?delete=<?= $p['id'] ?>" class="admin-btn-danger admin-btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>