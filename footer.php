<!-- Footer section -->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>Groco <i class="fa fa-shopping-basket"></i></h3>
            <p>Best Online Grocery Store in India. Save Big on Grocery Shopping Shop by Category.</p>
            <div class="share">
                <a href="#" class="fa fa-facebook"></a>
                <a href="#" class="fa fa-twitter"></a>
                <a href="#" class="fa fa-linkedin"></a>
                <a href="#" class="fa fa-instagram"></a>
            </div>
        </div>
        <div class="box">
            <h3>contact info</h3>
            <a href="#" class="links"><i class="fa fa-phone"></i>+91 9264366450</a>
            <a href="#" class="links"><i class="fa fa-envelope"></i> info@example.com</a>
            <a href="#" class="links"><i class="fa fa-map-marker"></i> pune, India</a>
        </div>
        <div class="box">
            <h3>Quick links</h3>
            <a href="index.php#home" class="links"><i class="fa fa-arrow-right"></i> Home</a>
            <a href="index.php#features" class="links"><i class="fa fa-arrow-right"></i> Features</a>
            <a href="index.php#products" class="links"><i class="fa fa-arrow-right"></i> Products</a>
            <a href="index.php#categories" class="links"><i class="fa fa-arrow-right"></i> Categories</a>
            <a href="index.php#review" class="links"><i class="fa fa-arrow-right"></i> Reviews</a>
            <a href="index.php#blogs" class="links"><i class="fa fa-arrow-right"></i> Blogs</a>
        </div>
        <div class="box">
            <h3>Newsletter</h3>
            <p>subscribe for latest updates</p>
            <input type="email" placeholder="your email" class="email">
            <input type="submit" value="subscribe" class="btn">
            <img src="image/payment.png" class="payment-img" alt="payment">
        </div>
    </div>
</section>

<!-- Popup Modal for Read More (Features & Blogs) -->
<div id="infoModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 id="modalTitle"></h2>
        <p id="modalDescription"></p>
    </div>
</div>

<!-- Popup Modal for Checkout (Shop Now) -->
<div id="checkoutModal" class="modal">
    <div class="modal-content checkout-modal">
        <span class="close-modal checkout-close">&times;</span>
        <h2>Complete Your Order</h2>
        <form id="checkoutForm" action="checkout.php" method="post">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" id="customerName" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Delivery Address *</label>
                <textarea name="address" id="customerAddress" required placeholder="Enter complete address" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" id="customerPhone" placeholder="Optional">
            </div>
            <div class="form-group">
                <label>Email (optional)</label>
                <input type="email" name="email" id="customerEmail" placeholder="Email">
            </div>
            <button type="submit" class="btn btn-submit">Place Order</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
    // ========== SWIPER INITIALIZATION ==========
    var productSwiper = new Swiper(".product-slider", {
        loop: true,
        spaceBetween: 20,
        autoplay: { delay: 4500, disableOnInteraction: false },
        breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1020: { slidesPerView: 3 } }
    });
    var reviewSwiper = new Swiper(".review-slider", {
        loop: true,
        spaceBetween: 20,
        autoplay: { delay: 4500, disableOnInteraction: false },
        breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1020: { slidesPerView: 3 } }
    });

    // ========== ADD TO CART (UNIVERSAL) ==========
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let id, name, price, image;
            // Try to get data from button itself
            if (this.dataset.id) {
                id = this.dataset.id;
                name = this.dataset.name;
                price = this.dataset.price;
                image = this.dataset.img;
            } else {
                // Fallback: find parent .box or .product-card
                const container = this.closest('.box') || this.closest('.product-card');
                if (container) {
                    id = container.dataset.id;
                    name = container.dataset.name;
                    price = container.dataset.price;
                    image = container.dataset.img;
                }
            }
            if (!id) {
                console.error("Product data not found");
                return;
            }
            fetch('cart-add.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&name=${encodeURIComponent(name)}&price=${price}&image=${encodeURIComponent(image)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) cartCount.innerText = data.cart_count;
                    alert(`${name} added to cart!`);
                } else {
                    alert('Error adding to cart');
                }
            })
            .catch(err => console.error(err));
        });
    });

    // ========== READ MORE MODAL (Features & Blogs) ==========
    const featureDetails = {
        organic: { title: "🌱 Fresh & Organic Products", description: "We source directly from local organic farms. Every product is chemical‑free and packed with natural goodness." },
        delivery: { title: "🚚 Fast Delivery Service", description: "Same‑day delivery in most areas. Track your order in real time." },
        payment: { title: "💳 Easy Payment Options", description: "UPI, cards, cash on delivery – secure and hassle‑free payments." }
    };
    const blogDetails = {
        'organic-fruits': { title: "🍊 Fresh & Organic Fruits", description: "Organic fruits are grown without pesticides, offering more nutrients and better taste." },
        'healthy-eating': { title: "🥗 Healthy Eating Tips", description: "Incorporate more fruits and vegetables, stay hydrated, and plan your meals." },
        'seasonal-veg': { title: "🌽 Seasonal Vegetables Guide", description: "Eat vegetables in season for better flavour, nutrition, and value." }
    };
    function openInfoModal(key, type) {
        const modal = document.getElementById('infoModal');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');
        let content = (type === 'blog') ? blogDetails[key] : featureDetails[key];
        if (content) {
            title.innerHTML = content.title;
            desc.innerHTML = content.description;
        } else {
            title.innerHTML = "Information";
            desc.innerHTML = "Learn more about our quality products and services.";
        }
        modal.style.display = 'block';
    }
    document.querySelectorAll('.read-more-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            openInfoModal(btn.dataset.feature, 'feature');
        });
    });
    document.querySelectorAll('.blog-read-more-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            openInfoModal(btn.dataset.blog, 'blog');
        });
    });

    // ========== CHECKOUT MODAL (Shop Now) ==========
    const checkoutModal = document.getElementById('checkoutModal');
    document.querySelectorAll('.shop-now-trigger, .category-shop-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            if (checkoutModal) checkoutModal.style.display = 'block';
        });
    });
    // Close modals
    document.querySelectorAll('.close-modal').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            document.getElementById('infoModal').style.display = 'none';
            if (checkoutModal) checkoutModal.style.display = 'none';
        });
    });
    window.addEventListener('click', e => {
        if (e.target === document.getElementById('infoModal')) document.getElementById('infoModal').style.display = 'none';
        if (checkoutModal && e.target === checkoutModal) checkoutModal.style.display = 'none';
    });
</script>
</body>
</html>