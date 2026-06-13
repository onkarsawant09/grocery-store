# grocery-store
The glossary is a reference section that defines all key technical and domain‑specific terms used in the store management system. It includes explanations of words like Product, Inventory, Stock, Supplier, Purchase Order, Sales Order, Customer, Cart, Checkout, Invoice, Payment, Return, Discount, Tax, Barcode, SKU, Reorder Level, Warehouse, and Admin. The glossary helps readers, developers, and end‑users understand the terminology consistently, reduces ambiguity, and serves as a quick reference throughout the project report and system documentation.
Here is a **complete, professional README.md** for your **Grocery Store – Fresh & Organic** frontend project, similar in style to the MyCampus README.

---

```markdown
# 🛒 Fresh & Organic Grocery Store – Frontend Demo

![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?logo=javascript&logoColor=black)
![Swiper](https://img.shields.io/badge/Swiper-11.x-6332F6?logo=swiper&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

> *A responsive, interactive frontend grocery store website with shopping cart, product sliders, modal popups, and a seamless checkout experience.*

---

## 📌 Overview

This project is a fully client‑side **grocery store website** designed to showcase fresh and organic products. It features a modern, mobile‑first layout with a product carousel, category grid, customer reviews, blog section, and a fully functional shopping cart that stores items in `sessionStorage`. Users can browse products, add them to the cart, adjust quantities, remove items, and complete a mock checkout. The website also includes “Read More” modals for features and blog posts, providing detailed content without leaving the page.

**Motto:** *Fresh & Organic – Quality You Can Trust*

---

## ✨ Key Features

### For Visitors / Customers
- 🛍️ **Product Browsing** – View products in a touch‑friendly Swiper carousel (two rows).
- ➕ **Add to Cart** – Click the “add to cart” button on any product.
- 📦 **Cart Management** – Adjust item quantities, delete items, see total price.
- 💳 **Checkout Modal** – Enter name, address, phone to place a dummy order (alert with order summary).
- 📖 **Read More Modals** – Features (fresh & organic, fast delivery, easy payment) and blog posts open a modal with rich descriptions.
- 🔍 **Search Bar** – Search products (UI ready, can be extended).
- 👤 **Login Form** – UI placeholder for future authentication.
- 📱 **Fully Responsive** – Works perfectly on desktop, tablet, and mobile.

### For Developers / Maintainers
- 🧩 **Modular JavaScript** – Cart logic, modal handling, event listeners, and Swiper initialisation.
- 💾 **Session Storage** – Cart persists across page refreshes.
- 🎨 **Custom CSS** – Clean, modern design with green theme, shadows, hover effects.
- 🌐 **No Backend** – Pure frontend, easy to deploy anywhere (Netlify, Vercel, GitHub Pages).

---

## 🛠️ Technology Stack

| Layer       | Technologies |
|-------------|--------------|
| **Markup**  | HTML5 |
| **Styling** | CSS3, Custom properties (variables), Flexbox, Grid, Media queries |
| **Interactivity** | JavaScript (ES6), DOM manipulation, SessionStorage |
| **Libraries** | Swiper.js (product & review carousels), FontAwesome (icons) |
| **Fonts**    | Google Fonts (Poppins) |
| **Icons**    | FontAwesome 4.7 |
| **Deployment** | Any static hosting (GitHub Pages, Netlify) |

---

## 📂 Project Structure

```
grocery-store/
├── index.html              # Main HTML file
├── css/
│   └── style.css           # All custom styles
├── js/
│   └── script.js           # Cart, modals, sliders, event handlers
├── image/                  # Product images, feature icons, banners
│   ├── product-1.png ... product-8.png
│   ├── feature-img-1.png, feature-img-2.png, feature-img-3.png
│   ├── cat-1.png ... cat-4.png
│   ├── blog-1.jpg, blog-2.jpg, blog-3.jpg
│   ├── banner-img.jpg
│   ├── pic-9.jpg
│   └── payment.png
└── README.md
```

---

## 🚀 Installation & Setup

### Prerequisites
- Any modern web browser (Chrome, Firefox, Edge, Safari)
- (Optional) Local web server (e.g., Live Server extension for VS Code)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/grocery-store.git
   cd grocery-store
   ```

2. **Open the website**
   - Simply double‑click `index.html` – it will open in your default browser.
   - Or use a live server for the best experience:
     ```bash
     npx live-server
     ```

3. **Start shopping** – Browse products, add to cart, and test the checkout modal.

> ⚠️ No database or backend is required. All cart data is stored in your browser’s `sessionStorage` and will be cleared when you close the tab.

---

## 🧪 How to Use

### For Customers
1. **Browse Products** – Scroll through the product sliders (autoplay enabled).
2. **Add to Cart** – Click the green “add to cart” button on any product. A notification will appear.
3. **View Cart** – Click the shopping cart icon (top right) – the cart drawer opens.
4. **Update Quantity** – Use the `+` / `-` buttons inside the cart item.
5. **Remove Item** – Click the trash icon to delete an item.
6. **Checkout** – Click the “checkout” button in the cart. Fill the modal form and place the order. A summary alert will appear, and the cart clears.
7. **Read More** – Click “read more” under features or blog posts to open a modal with detailed information.
8. **Login / Search** – These are UI placeholders (no actual functionality yet).

### For Developers
- Customise product data in `index.html` (update `data-id`, `data-name`, `data-price`, `data-img`).
- Modify feature/blog content in `script.js` under `featureDetails` and `blogDetails` objects.
- Adjust Swiper settings (autoplay speed, slides per view) in `script.js`.
- Extend with backend (PHP, Node.js) to handle real user accounts and payments.

---

## 📸 Screenshots

> *Place your actual screenshots in a `screenshots/` folder and reference them here.*

| Homepage | Product Slider | Cart Drawer |
|----------|----------------|-------------|
| ![Home](screenshots/home.png) | ![Products](screenshots/products.png) | ![Cart](screenshots/cart.png) |

| Read More Modal | Checkout Modal | Mobile View |
|----------------|----------------|--------------|
| ![Modal](screenshots/modal.png) | ![Checkout](screenshots/checkout.png) | ![Mobile](screenshots/mobile.png) |

---

## 🔧 Future Enhancements

- [ ] **User Authentication** – Real login/registration with backend.
- [ ] **Product Search** – Implement search functionality to filter products.
- [ ] **Payment Gateway Integration** – Razorpay or Stripe for real payments.
- [ ] **Order History** – Store previous orders in local storage or database.
- [ ] **Admin Panel** – Manage products, categories, and view orders.
- [ ] **Product Filtering** – Filter by category (vegetables, fruits, dairy, meat).
- [ ] **Wishlist** – Save favourite items.
- [ ] **Product Reviews** – Allow users to leave ratings and comments.
- [ ] **Multi‑language Support** – Hindi, Marathi, etc.

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👨‍💻 Author

**Onkar Sawant** – [GitHub](https://github.com/onkar-sawant)

**Project Guide:** Prof. A.S. Tanpure  
**Institution:** Hutatma Rajguru Mahavidyalaya, Rajgurunagar, Pune

---

## 🙏 Acknowledgements

- Swiper.js for smooth carousels.
- FontAwesome for high‑quality icons.
- Google Fonts for the Poppins typeface.
- All image sources (free for educational use).

---

## 📬 Contact

For any queries or suggestions, please create an issue on GitHub or reach out to the author.

**Live Demo:** *(if hosted, add URL)*  
**Project Report:** *(link to PDF if available)*

---

⭐ If you like this project, give it a star on GitHub!

*Happy shopping! 🛒🌱*
```
