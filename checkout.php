<?php
require_once 'config.php';
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) redirect('cart.php');
$cart = $_SESSION['cart'];
$total = array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, $cart));
$page_title = "Checkout";
require_once 'header.php';
?>
<style>
    .checkout-container { max-width: 800px; margin: 80px auto 40px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .checkout-title { color: #2e7d32; margin-bottom: 30px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    .order-summary { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .order-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
    .total { font-weight: bold; font-size: 1.2rem; margin-top: 10px; text-align: right; }
    .place-order-btn { background: #2e7d32; color: white; border: none; padding: 12px 25px; border-radius: 5px; font-size: 1rem; cursor: pointer; width: 100%; }
    @media (max-width: 768px) { .checkout-container { margin: 70px 15px; } }
</style>
<div class="checkout-container">
    <h2 class="checkout-title">Checkout</h2>
    <div class="order-summary">
        <h3>Order Summary</h3>
        <?php foreach($cart as $item): ?>
        <div class="order-item"><span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span><span>₹<?= number_format($item['price']*$item['quantity'],2) ?></span></div>
        <?php endforeach; ?>
        <div class="total">Total: ₹<?= number_format($total,2) ?></div>
    </div>
    <form method="post" action="place-order.php">
        <div class="form-group"><label>Full Name *</label><input type="text" name="name" required></div>
        <div class="form-group"><label>Email (optional)</label><input type="email" name="email"></div>
        <div class="form-group"><label>Phone *</label><input type="tel" name="phone" required></div>
        <div class="form-group"><label>Delivery Address *</label><textarea name="address" rows="3" required></textarea></div>
        <button type="submit" class="place-order-btn">Place Order</button>
    </form>
</div>
<?php require_once 'footer.php'; ?>