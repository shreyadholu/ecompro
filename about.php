<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Closetly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }

        .navbar .nav-items {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar .nav-items button {
            padding: 10px 20px;
            background: none;
            border: 2px solid #2c3e50;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .navbar .nav-items button:hover {
            background-color: #2c3e50;
            color: white;
        }

        .about-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .about-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .about-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .about-header p {
            font-size: 18px;
            color: #7f8c8d;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin: 60px 0;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card i {
            font-size: 40px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .feature-card p {
            color: #7f8c8d;
            line-height: 1.6;
        }

        .team-section {
            text-align: center;
            margin: 80px 0;
        }

        .team-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            margin-bottom: 40px;
            color: #2c3e50;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 60px 40px 30px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-section p, .footer-section a {
            color: #ecf0f1;
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: #3498db;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="homepage.php" class="logo">Closetly</a>
        <div class="nav-items">
            <button onclick="window.location.href='homepage.php'">Home</button>
            <button onclick="window.location.href='cart_system.php'">Cart</button>
            <button onclick="window.location.href='contact.php'">Contact</button>
        </div>
    </div>

    <!-- About Content -->
    <div class="about-container">
        <div class="about-header">
            <h1>About Closetly</h1>
            <p>Welcome to Closetly, your go-to destination for building a timeless, effortless, and versatile capsule wardrobe. </p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <i class="bi bi-stars"></i>
                <h3>Quality First</h3>
                <p>We curate only the finest quality products, ensuring that every item meets our high standards for durability and style.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-truck"></i>
                <h3>Fast Delivery</h3>
                <p>Experience lightning-fast shipping with our efficient delivery system, bringing fashion to your doorstep.</p>
            </div>
            <div class="feature-card">
                <i class="bi bi-heart"></i>
                <h3>Customer Care</h3>
                <p>Our dedicated support team is always ready to assist you with any questions or concerns you may have.</p>
            </div>
        </div>

        <div class="team-section">
            <h2>Our Story</h2>
            <p>We believe that fashion should be minimal, sustainable, and stress-free, allowing you to express your personal style without the clutter of an overflowing closet.</p>
            <p>At Closetly, we help you curate a wardrobe filled with high-quality essentials that mix and match seamlessly, making outfit planning simple and stylish. Whether you're a minimalist, a fashion enthusiast, or someone looking to embrace a more intentional approach to dressing, our platform provides expert guidance, styling tips, and wardrobe essentials tailored to your lifestyle.
            Join us in redefining fashion—less clutter, more style. Because with Closetly, less is truly more. ✨</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Closetly</h3>
                <p>Welcome to Closetly, your go-to destination for building a timeless, effortless, and versatile capsule wardrobe.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="about.php">About Us</a></p>
                <p><a href="contact.php">Contact</a></p>
                <p><a href="faq.php">FAQ</a></p>
                <p><a href="shipping.php">Shipping Info</a></p>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: info@closetly.com</p>
                <p>Phone: (555) 123-4567</p>
                <p>Address: 123 Fashion Street, Style City, ST 12345</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Closetly. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>