<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$user_id ORDER BY created_at DESC");
$page_title = "My Profile";
require_once 'header.php';
?>
<style>
    .profile-container { max-width: 1000px; margin: 80px auto 40px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .profile-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; border-bottom: 1px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
    .profile-header h2 { color: #2e7d32; }
    .edit-btn { background: #2e7d32; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    .info-section { margin-bottom: 30px; }
    .info-section p { margin: 10px 0; }
    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table th, .orders-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    .orders-table th { background: #f5f5f5; }
    .status-pending { color: #ffc107; }
    .status-completed { color: #28a745; }
    .status-cancelled { color: #dc3545; }
    @media (max-width: 768px) { .profile-container { margin: 70px 15px; } .profile-header { flex-direction: column; gap: 10px; } }
</style>
<div class="profile-container">
    <div class="profile-header">
        <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
        <a href="edit-profile.php" class="edit-btn">Edit Profile</a>
    </div>
    <div class="info-section">
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?: 'Not set') ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($user['address'] ?: 'Not set')) ?></p>
    </div>
    <h3>Order History</h3>
    <?php if($orders->num_rows == 0): ?>
        <p>No orders yet.</p>
    <?php else: ?>
        <table class="orders-table">
            <thead><tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th><th>Details</th></tr></thead>
            <tbody>
                <?php while($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    <td>₹<?= number_format($order['total_amount'],2) ?></td>
                    <td class="status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></td>
                    <td><a href="order-confirmation.php?id=<?= $order['id'] ?>">View</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <p><a href="logout.php" style="color:#dc3545;">Logout</a></p>
</div>
<?php require_once 'footer.php'; ?>