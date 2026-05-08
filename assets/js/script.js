function addToCart(productId, productName, productPrice, productImage) {
    fetch('cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&id=${productId}&name=${encodeURIComponent(productName)}&price=${productPrice}&image=${productImage}`
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('cart-count').innerText = data.cart_count;
        showAddToCartNotification(productName);
        loadCartDisplay(); // refresh cart popup
    });
}