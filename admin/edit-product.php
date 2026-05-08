<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id) {
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if(!$product) {
    header("Location: products.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name           = trim($_POST['name']);
    $description    = trim($_POST['description']);
    $price          = floatval($_POST['price']);
    $compare_price  = !empty($_POST['compare_price']) ? floatval($_POST['compare_price']) : 0;
    $category_id    = intval($_POST['category_id']);
    $featured       = intval($_POST['featured']);
    $image_path     = $product['image']; // keep old by default

    // Handle image upload if new file provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/products/";
        
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        if (!is_writable($target_dir)) {
            $_SESSION['error'] = "Upload directory is not writable.";
            header("Location: edit-product.php?id=$id");
            exit;
        }
        
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('prod_') . '.' . $file_ext;
            $target_file = $target_dir . $new_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Delete old image
                $old_path = "../" . $product['image'];
                if(file_exists($old_path) && is_file($old_path)) {
                    unlink($old_path);
                }
                $image_path = "uploads/products/" . $new_name;
            } else {
                $_SESSION['error'] = "Failed to upload new image. Check folder permissions.";
                header("Location: edit-product.php?id=$id");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image type. Allowed: jpg, jpeg, png, gif, webp.";
            header("Location: edit-product.php?id=$id");
            exit;
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['error'] = "Upload error: " . $_FILES['image']['error'];
        header("Location: edit-product.php?id=$id");
        exit;
    }

    $updateStmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, compare_price=?, category_id=?, image=?, featured=? WHERE id=?");
    $updateStmt->bind_param("ssddissi", $name, $description, $price, $compare_price, $category_id, $image_path, $featured, $id);
    
    if ($updateStmt->execute()) {
        $_SESSION['success'] = "Product updated successfully.";
        header("Location: products.php");
        exit;
    } else {
        $_SESSION['error'] = "Database error: " . $updateStmt->error;
        header("Location: edit-product.php?id=$id");
        exit;
    }
    $updateStmt->close();
}

$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .current-image { max-width: 100px; margin: 10px 0; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Edit Product</h1>
        <div class="admin-nav">
            <a href="products.php">Back to Products</a>
        </div>
    </div>
    <div class="admin-container">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group"><label>Product Name</label><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea></div>
            <div class="form-group"><label>Price (₹)</label><input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required></div>
            <div class="form-group"><label>Compare Price (₹)</label><input type="number" step="0.01" name="compare_price" value="<?= $product['compare_price'] ?>"></div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <?php while($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <img src="../<?= htmlspecialchars($product['image']) ?>" class="current-image" alt="Current product image">
            </div>
            <div class="form-group">
                <label>Change Image (leave empty to keep current)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <label>Featured?</label>
                <select name="featured">
                    <option value="0" <?= $product['featured'] == 0 ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= $product['featured'] == 1 ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
            <button type="submit" name="update" class="admin-btn">Update Product</button>
        </form>
    </div>
</body>
</html>