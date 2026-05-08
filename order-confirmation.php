<?php
require_once 'config.php';
$id = (int)$_GET['id'];
$order = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
if (!$order) die("Order not found");
$items = $conn->query("SELECT * FROM order_items WHERE order_id=$id");
$page_title = "Order Confirmation";
require_once 'header.php';
?>
<style>
    .confirmation-container { max-width: 800px; margin: 80px auto 40px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
    .success-icon { font-size: 4rem; color: #28a745; margin-bottom: 20px; }
    .order-details { text-align: left; margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 8px; }
    .order-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .order-table th, .order-table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    .continue-btn { background: #2e7d32; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px; }
    @media (max-width: 768px) { .confirmation-container { margin: 70px 15px; } }
</style>
<div class="confirmation-container">
    <div class="success-icon">✓</div>
    <h2>Thank you for your order!</h2>
    <p>Your order has been placed successfully.</p>
    <div class="order-details">
        <p><strong>Order #:</strong> <?= $order['id'] ?></p>
        <p><strong>Date:</strong> <?= date('d M Y h:i A', strtotime($order['created_at'])) ?></p>
        <p><strong>Delivery Address:</strong> <?= nl2br(htmlspecialchars($order['customer_address'])) ?></p>
        <h3>Order Items</h3>
        <table class="order-table">
            <thead><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr></thead>
            <tbody>
                <?php while($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₹<?= number_format($item['price'],2) ?></td>
                    <td>₹<?= number_format($item['price']*$item['quantity'],2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr><td colspan="3" style="text-align:right;"><strong>Total</strong></td><td><strong>₹<?= number_format($order['total_amount'],2) ?></strong></td></tr>
            </tbody>
        </table>
    </div>
    <a href="index.php" class="continue-btn">Continue Shopping</a>
</div>
<?php require_once 'footer.php'; ?>