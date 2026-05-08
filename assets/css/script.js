// ========== CART FUNCTIONALITY (AJAX with PHP) ==========
function addToCart(productId, productName, productPrice, productImage) {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&id=${productId}&name=${encodeURIComponent(productName)}&price=${productPrice}&image=${productImage}`
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('cart-count').innerText = data.cart_count;
        showAddToCartNotification(productName);
        loadCartDisplay();
    });
}

function loadCartDisplay() {
    fetch('cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get'
    })
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('shopping-cart-container');
        if (!container) return;
        if (data.items.length === 0) {
            container.innerHTML = '<div class="empty-cart-msg">Your cart is empty</div>';
            return;
        }
        let html = '';
        data.items.forEach(item => {
            html += `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="content">
                        <h3>${item.name}</h3>
                        <div><span class="price">₹${item.price.toFixed(2)}/-</span><span class="quantity">Qty: ${item.quantity}</span></div>
                        <div class="quantity-controls">
                            <button class="qty-btn dec-btn" data-id="${item.id}">-</button>
                            <span>${item.quantity}</span>
                            <button class="qty-btn inc-btn" data-id="${item.id}">+</button>
                        </div>
                    </div>
                    <i class="fa fa-trash delete-item" data-id="${item.id}"></i>
                </div>
            `;
        });
        html += `<div class="total">Total : ₹${data.total.toFixed(2)}/-</div>
                 <a href="#" class="btn checkout-btn">checkout</a>`;
        container.innerHTML = html;
        attachCartEvents();
    });
}

function attachCartEvents() {
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = parseInt(btn.dataset.id);
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=remove&id=${id}`
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('cart-count').innerText = data.cart_count;
                loadCartDisplay();
            });
        });
    });
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = parseInt(btn.dataset.id);
            const change = btn.classList.contains('inc-btn') ? 1 : -1;
            const qtySpan = btn.parentElement.querySelector('span');
            let newQty = parseInt(qtySpan.innerText) + change;
            if (newQty < 1) newQty = 0;
            fetch('cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update&id=${id}&quantity=${newQty}`
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('cart-count').innerText = data.cart_count;
                loadCartDisplay();
            });
        });
    });
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openCheckoutModal();
        });
    }
}

// Add to cart button listeners (dynamic)
function attachAddToCartListeners() {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.removeEventListener('click', handleAddToCart);
        btn.addEventListener('click', handleAddToCart);
    });
}
function handleAddToCart(e) {
    e.preventDefault();
    const box = e.target.closest('.box');
    if (box) {
        const id = box.dataset.id;
        const name = box.dataset.name;
        const price = parseFloat(box.dataset.price);
        const image = box.dataset.img;
        addToCart(id, name, price, image);
    } else if (e.target.dataset.id) {
        // for single product page
        addToCart(e.target.dataset.id, e.target.dataset.name, e.target.dataset.price, e.target.dataset.img);
    }
}

// ... rest of your existing JS (search, modal, etc.) remains same, but ensure cart functions are replaced.

// Load cart on page load
document.addEventListener('DOMContentLoaded', () => {
    loadCartDisplay();
    attachAddToCartListeners();
    // ... other init code
});