<?php
$page_title = "Grocery Store - Fresh & Organic";
require_once 'config.php';
require_once 'header.php';

$features = $conn->query("SELECT * FROM features LIMIT 3");
$products_featured = $conn->query("SELECT * FROM products WHERE featured=1 LIMIT 8");
$products_all = $conn->query("SELECT * FROM products LIMIT 8");
$categories = $conn->query("SELECT * FROM categories");
$reviews = $conn->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 3");
$blogs = $conn->query("SELECT * FROM blogs ORDER BY id DESC LIMIT 3");
?>

<!-- Home banner section -->
<section class="home" id="home">
    <div class="content">
        <h3>Fresh and <span> organic </span> products for you</h3>
        <p>Many people like to eat organic food because they think it is better for their health and for the environment...</p>
        <a href="#" class="btn shop-now-trigger">shop now</a>
    </div>
</section>

<!-- Features section -->
<section class="features" id="features">
    <h1 class="heading">our <span> features</span></h1>
    <div class="box-container">
        <?php while($feat = $features->fetch_assoc()): ?>
        <div class="box">
            <img src="<?= htmlspecialchars($feat['image']) ?>" alt="">
            <h3><?= htmlspecialchars($feat['title']) ?></h3>
            <p><?= htmlspecialchars(substr($feat['description'],0,120)) ?>...</p>
            <a href="#" class="btn read-more-btn" data-feature="<?= $feat['id'] ?>">read more</a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Products section (with Swiper) -->
<section class="products" id="products">
    <h1 class="heading">our <span>products</span></h1>
    <div class="swiper product-slider">
        <div class="swiper-wrapper">
            <?php while($prod = $products_featured->fetch_assoc()): ?>
            <div class="swiper-slide box" data-id="<?= $prod['id'] ?>" data-name="<?= htmlspecialchars($prod['name']) ?>" data-price="<?= $prod['price'] ?>" data-img="<?= htmlspecialchars($prod['image']) ?>">
                <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                <h1><?= htmlspecialchars($prod['name']) ?></h1>
                <div class="price">₹<?= $prod['price'] ?>/- - ₹<?= $prod['compare_price'] ?>/-</div>
                <div class="stars">★★★★☆</div>
                <a href="#" class="btn add-to-cart-btn">add to cart</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="swiper product-slider">
        <div class="swiper-wrapper">
            <?php while($prod = $products_all->fetch_assoc()): ?>
            <div class="swiper-slide box" data-id="<?= $prod['id'] ?>" data-name="<?= htmlspecialchars($prod['name']) ?>" data-price="<?= $prod['price'] ?>" data-img="<?= htmlspecialchars($prod['image']) ?>">
                <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                <h1><?= htmlspecialchars($prod['name']) ?></h1>
                <div class="price">₹<?= $prod['price'] ?>/- - ₹<?= $prod['compare_price'] ?>/-</div>
                <div class="stars">★★★★☆</div>
                <a href="#" class="btn add-to-cart-btn">add to cart</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories" id="categories">
    <h1 class="heading">product <span>categories</span></h1>
    <div class="box-container">
        <?php while($cat = $categories->fetch_assoc()): ?>
        <div class="box">
            <img src="<?= htmlspecialchars($cat['image']) ?>" alt="">
            <h3><?= htmlspecialchars($cat['name']) ?></h3>
            <p>upto 45% off</p>
            <a href="category.php?id=<?= $cat['id'] ?>" class="btn">shop now</a>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Reviews -->
<section class="review" id="review">
    <h1 class="heading">customer's <span>review</span></h1>
    <div class="swiper review-slider">
        <div class="swiper-wrapper">
            <?php while($rev = $reviews->fetch_assoc()): ?>
            <div class="swiper-slide box">
                <img src="image/pic-9.jpg" alt="">
                <p><?= htmlspecialchars($rev['comment']) ?></p>
                <h3>Customer</h3>
                <div class="stars"><?= str_repeat('★', $rev['rating']) . str_repeat('☆', 5-$rev['rating']) ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Blogs -->
<section class="blogs" id="blogs">
    <h1 class="heading">our <span>Blogs</span></h1>
    <div class="box-container">
        <?php while($blog = $blogs->fetch_assoc()): ?>
        <div class="box">
            <img src="<?= htmlspecialchars($blog['image']) ?>" alt="">
            <div class="content">
                <div class="icons">
                    <a href="#"><i class="fa fa-user"></i> <?= htmlspecialchars($blog['author']) ?></a>
                    <a href="#"><i class="fa fa-calendar"></i> <?= date('jS M, Y', strtotime($blog['created_at'])) ?></a>
                </div>
                <h3><?= htmlspecialchars($blog['title']) ?></h3>
                <p><?= htmlspecialchars(substr($blog['content'],0,100)) ?>...</p>
                <a href="#" class="btn blog-read-more-btn" data-blog="<?= $blog['id'] ?>">read more</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once 'footer.php'; ?>