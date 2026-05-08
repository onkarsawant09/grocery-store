<?php
if (!isset($conn)) require_once 'config.php';
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Grocery Store' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="header">
    <a href="index.php" class="logo"><i class="fa fa-shopping-basket"></i> Grocery</a>
    <nav class="navbar">
        <a href="index.php#home">home</a>
        <a href="index.php#features">features</a>
        <a href="index.php#products">products</a>
        <a href="index.php#categories">categories</a>
        <a href="index.php#review">review</a>
        <a href="index.php#blogs">blogs</a>
    </nav>
    <div class="icons">
        <div class="fa fa-bars" id="menu-btn"></div>
        <div class="fa fa-search" id="search-btn"></div>
        <!-- Cart Icon with link to cart.php -->
        <div class="cart-icon-wrapper">
            <a href="cart.php" style="color: inherit; text-decoration: none;">
                <i class="fa fa-shopping-cart"></i>
            </a>
            <span class="cart-count" id="cart-count"><?= $cart_count ?></span>
        </div>
      <a href="login.php">  <div class="fa fa-user" id="login-btn"></div></a>
    </div>
    <form class="search-form" action="search.php" method="get">
        <input type="search" name="q" id="search-box" placeholder="Search Here...">
        <label for="search-box" class="fa fa-search"></label>
    </form>
    <div class="shopping-cart" id="shopping-cart-container">
        <div class="empty-cart-msg">Your cart is empty</div>
    </div>
    <form action="login.php" class="login-form" method="post">
        <h3>login now</h3>
        <input type="email" name="email" placeholder="enter your email" class="box" required>
        <input type="password" name="password" placeholder="enter your Password" class="box" required>
        <p>forget your password <a href="forgot.php">click here</a></p>
        <p>Don't have an account? <a href="register.php">create now</a></p>
        <input type="submit" value="login now" class="btn">
    </form>
</header>