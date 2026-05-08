<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name           = trim($_POST['name']);
    $description    = trim($_POST['description']);
    $price          = floatval($_POST['price']);
    $compare_price  = !empty($_POST['compare_price']) ? floatval($_POST['compare_price']) : 0;
    $category_id    = intval($_POST['category_id']);
    $featured       = intval($_POST['featured']);

    if(empty($name) || $price <= 0) {
        $_SESSION['error'] = "Product name and valid price are required.";
        header("Location: products.php");
        exit;
    }

    // Image upload
    $target_dir = "../uploads/products/";
    $image_path = "";

    // Create directory if not exists (recursive)
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Check if directory is writable
    if (!is_writable($target_dir)) {
        $_SESSION['error'] = "Upload directory is not writable: " . $target_dir;
        header("Location: products.php");
        exit;
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('prod_') . '.' . $file_ext;
            $target_file = $target_dir . $new_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $image_path = "uploads/products/" . $new_name;
            } else {
                $_SESSION['error'] = "Failed to move uploaded file. Check folder permissions.";
                header("Location: products.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image type. Allowed: jpg, jpeg, png, gif, webp.";
            header("Location: products.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Please select an image file.";
        header("Location: products.php");
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, compare_price, category_id, image, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddiss", $name, $description, $price, $compare_price, $category_id, $image_path, $featured);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully.";
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
    }
    $stmt->close();
    header("Location: products.php");
    exit;
} else {
    header("Location: products.php");
    exit;
}
?>