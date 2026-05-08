<?php
require_once 'config.php';
$id = (int)$_GET['id'];
$category = $conn->query("SELECT * FROM categories WHERE id=$id")->fetch_assoc();
if (!$category) die("Category not found");
$products = $conn->query("SELECT * FROM products WHERE category_id=$id");
$page_title = htmlspecialchars($category['name']);
require_once 'header.php';
?>
<style>
    .category-container { max-width: 1200px; margin: 80px auto 40px; padding: 20px; }
    .category-title { color: #2e7d32; margin-bottom: 30px; text-align: center; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
    .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s; text-align: center; padding: 15px; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
    .product-card img { width: 100%; height: 180px; object-fit: contain; margin-bottom: 10px; }
    .product-card h4 { font-size: 1.1rem; margin: 10px 0; color: #333; }
    .product-card .price { font-size: 1.2rem; font-weight: bold; color: #2e7d32; margin: 8px 0; }
    .add-to-cart-btn { background: #2e7d32; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; transition: background 0.2s; }
    .add-to-cart-btn:hover { background: #1b5e20; }
    @media (max-width: 768px) { .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); } .product-card img { height: 140px; } }
</style>
<div class="category-container">
    <h2 class="category-title"><?= htmlspecialchars($category['name']) ?></h2>
    <div class="product-grid">
        <?php while($prod = $products->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?= $prod['image'] ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
            <h4><?= htmlspecialchars($prod['name']) ?></h4>
            <div class="price">₹<?= number_format($prod['price'],2) ?></div>
            <button class="add-to-cart-btn" data-id="<?= $prod['id'] ?>" data-name="<?= htmlspecialchars($prod['name']) ?>" data-price="<?= $prod['price'] ?>" data-img="<?= $prod['image'] ?>">Add to Cart</button>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>