<?php
require_once 'config.php';
$q = $_GET['q'] ?? '';
$products = $conn->query("SELECT * FROM products WHERE name LIKE '%$q%' OR description LIKE '%$q%'");
$page_title = "Search: $q";
require_once 'header.php';
?>
<style>
    .search-container { max-width: 1200px; margin: 80px auto 40px; padding: 20px; }
    .search-title { color: #2e7d32; margin-bottom: 30px; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px; }
    .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; padding: 15px; transition: transform 0.2s; }
    .product-card:hover { transform: translateY(-5px); }
    .product-card img { width: 100%; height: 180px; object-fit: contain; }
    .product-card h4 { margin: 10px 0; }
    .price { font-weight: bold; color: #2e7d32; margin: 8px 0; }
    .add-to-cart-btn { background: #2e7d32; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
    .no-results { text-align: center; padding: 50px; color: #666; }
    @media (max-width: 768px) { .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); } }
</style>
<div class="search-container">
    <h2 class="search-title">Search results for "<?= htmlspecialchars($q) ?>"</h2>
    <?php if($products->num_rows == 0): ?>
        <div class="no-results">No products found. Try another keyword.</div>
    <?php else: ?>
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
    <?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>