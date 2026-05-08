<?php
session_start();
if(!isset($_SESSION['admin'])) header("Location: login.php");
require_once '../config.php';

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
    header("Location: orders.php");
    exit;
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .status-select { padding: 5px; border-radius: 4px; }
        .status-pending { background: #ffc107; color: #000; padding: 3px 8px; border-radius: 4px; }
        .status-processing { background: #17a2b8; color: white; }
        .status-completed { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Manage Orders</h1>
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
        <h2>All Orders</h2>
        <table class="admin-table">
            <thead><tr><th>Order #</th><th>Customer</th><th>Address</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php while($order = $orders->fetch_assoc()): 
                    $statusClass = '';
                    switch($order['status']) {
                        case 'pending': $statusClass = 'status-pending'; break;
                        case 'processing': $statusClass = 'status-processing'; break;
                        case 'completed': $statusClass = 'status-completed'; break;
                        case 'cancelled': $statusClass = 'status-cancelled'; break;
                    }
                ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?><br><small><?= $order['customer_email'] ?><br><?= $order['customer_phone'] ?></small></td>
                    <td><?= nl2br(htmlspecialchars($order['customer_address'])) ?></td>
                    <td>₹<?= number_format($order['total_amount'],2) ?></td>
                    <td><span class="<?= $statusClass ?>"><?= ucfirst($order['status']) ?></span></td>
                    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    <td>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="status-select">
                                <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
                                <option value="processing" <?= $order['status']=='processing'?'selected':'' ?>>Processing</option>
                                <option value="completed" <?= $order['status']=='completed'?'selected':'' ?>>Completed</option>
                                <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="admin-btn-sm">Update</button>
                        </form>
                        <a href="order-details.php?id=<?= $order['id'] ?>" class="admin-btn-sm">View Items</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>