<?php
require_once 'config.php';
$cart_items = $_SESSION['cart'] ?? [];
$total = array_sum(array_map(function($i){ return $i['price'] * $i['quantity']; }, $cart_items));
$page_title = "My Cart";
require_once 'header.php';
?>
<style>
    .cart-container { max-width: 1000px; margin: 80px auto 40px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .cart-title { color: #2e7d32; margin-bottom: 30px; }
    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th, .cart-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    .cart-table th { background: #f5f5f5; }
    .product-img { width: 60px; height: 60px; object-fit: contain; }
    .quantity-input { width: 60px; padding: 5px; text-align: center; }
    .remove-link { color: #dc3545; text-decoration: none; }
    .cart-total { text-align: right; font-size: 1.2rem; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
    .checkout-btn { background: #2e7d32; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; margin-top: 20px; display: inline-block; text-decoration: none; }
    .empty-cart { text-align: center; padding: 50px; color: #666; }
    @media (max-width: 768px) { .cart-table th, .cart-table td { padding: 8px; } .product-img { width: 40px; height: 40px; } .quantity-input { width: 50px; } }
</style>
<div class="cart-container">
    <h2 class="cart-title">Your Shopping Cart</h2>
    <?php if(empty($cart_items)): ?>
        <div class="empty-cart">Your cart is empty. <a href="index.php">Continue shopping</a></div>
    <?php else: ?>
        <form method="post" action="cart-update.php" id="cart-form">
            <table class="cart-table">
                <thead>
                    <tr><th>Product</th><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach($cart_items as $item): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['image']) ?>" class="product-img"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>₹<?= number_format($item['price'],2) ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="quantity-input" data-id="<?= $item['id'] ?>">
                        </td>
                        <td class="subtotal">₹<?= number_format($item['price'] * $item['quantity'],2) ?></td>
                        <td><a href="cart-remove.php?id=<?= $item['id'] ?>" class="remove-link" onclick="return confirm('Remove item?')">Remove</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-total">Total: ₹<?= number_format($total,2) ?></div>
            <button type="submit" class="checkout-btn" formaction="cart-update.php" formmethod="post">Update Cart</button>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </form>
    <?php endif; ?>
</div>
<script>
    // Auto-update quantity on change without submit button (optional)
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            let id = this.dataset.id;
            let qty = this.value;
            fetch('cart-update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&quantity=${qty}`
            }).then(() => location.reload());
        });
    });
</script>
<?php require_once 'footer.php'; ?>