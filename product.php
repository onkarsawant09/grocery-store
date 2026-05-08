<?php
require_once 'config.php';
$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) die("Product not found");
$reviews = $conn->query("SELECT r.*, u.name as user_name FROM reviews r LEFT JOIN users u ON r.user_id=u.id WHERE r.product_id=$id ORDER BY r.created_at DESC");
$page_title = htmlspecialchars($product['name']);
require_once 'header.php';
?>
<style>
    .product-container { max-width: 1000px; margin: 80px auto 40px; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .product-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .product-image img { width: 100%; max-height: 400px; object-fit: contain; }
    .product-info h1 { color: #333; margin-bottom: 10px; }
    .price { font-size: 1.5rem; font-weight: bold; color: #2e7d32; margin: 15px 0; }
    .old-price { text-decoration: line-through; color: #999; font-size: 1rem; margin-left: 10px; }
    .description { margin: 20px 0; line-height: 1.6; color: #555; }
    .add-to-cart-btn { background: #2e7d32; color: white; border: none; padding: 12px 25px; border-radius: 5px; font-size: 1rem; cursor: pointer; }
    .reviews-section { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; }
    .review-item { margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px; }
    .review-rating { color: #ffc107; margin-bottom: 5px; }
    .review-author { font-weight: bold; margin-top: 10px; color: #2e7d32; }
    @media (max-width: 768px) { .product-detail { grid-template-columns: 1fr; } .product-container { margin: 70px 15px; } }
</style>
<div class="product-container">
    <div class="product-detail">
        <div class="product-image"><img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></div>
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price">₹<?= number_format($product['price'],2) ?> <span class="old-price">₹<?= number_format($product['compare_price'],2) ?></span></div>
            <div class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
            <button class="add-to-cart-btn" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>" data-img="<?= $product['image'] ?>">Add to Cart</button>
        </div>
    </div>
    <div class="reviews-section">
        <h3>Customer Reviews</h3>
        <?php if($reviews->num_rows == 0): ?>
            <p>No reviews yet.</p>
        <?php else: while($rev = $reviews->fetch_assoc()): ?>
        <div class="review-item">
            <div class="review-rating"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']) ?></div>
            <div class="review-comment"><?= htmlspecialchars($rev['comment']) ?></div>
            <div class="review-author">- <?= htmlspecialchars($rev['user_name'] ?? 'Anonymous') ?></div>
        </div>
        <?php endwhile; endif; ?>
        <?php if(isLoggedIn()): ?>
        <form method="post" action="submit-review.php" style="margin-top: 20px;">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <select name="rating" required><option value="5">★★★★★</option><option value="4">★★★★☆</option><option value="3">★★★☆☆</option><option value="2">★★☆☆☆</option><option value="1">★☆☆☆☆</option></select>
            <textarea name="comment" placeholder="Write your review..." rows="3" style="width:100%; margin:10px 0; padding:8px;"></textarea>
            <button type="submit" class="add-to-cart-btn">Submit Review</button>
        </form>
        <?php else: ?>
        <p><a href="login.php">Login</a> to write a review.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>