<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$categories = [
    "Tops" => ["image" => "uploads/topss/white_tshirt.jpg", "link" => "tops_page.php"],
    "Bottomwear" => ["image" => "uploads/bottomwear/widelegjeans.jpeg", "link" => "bottomwear_page.php"],
    "Dresses" => ["image" => "uploads/dresses/slipdress.jpg", "link" => "dresses.php"],
    "Footwear" => ["image" => "uploads/footwear/blackheels.jpg", "link" => "footwear.php"],
    "Bags & Clutches" => ["image" => "uploads/bags/blackclutch.jpg", "link" => "bags.php"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly - Home Pages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
        .navbar .nav-items .form-control {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            width: 250px;
            font-size: 16px;
        }

        .categories-container {
            padding: 60px 40px;
            text-align: center;
            background-color: #fff;
        }
        .categories-title {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 40px;
            color: #2c3e50;
            font-family: 'Playfair Display', serif;
        }
        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .category-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            aspect-ratio: 3/4;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }
        .category-card:hover img {
            transform: scale(1.1);
        }
        .category-name {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: rgba(255, 255, 255, 0.95);
            color: #2c3e50;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            backdrop-filter: blur(5px);
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        /* Footer Styles */
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
    <div class="logo">Closetly</div>
    <div class="nav-items">
        <button onclick="window.location.href='cart_system.php'">Cart</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <form action="search.php" method="GET" class="d-flex">
            <input class="form-control" type="search" name="query" placeholder="Search..." required>
        </form>
    </div>
</div>

<!-- Categories Section -->
<div class="categories-container">
    <h2 class="categories-title">Shop by Category</h2>
    <div class="categories">
        <?php foreach ($categories as $name => $details): ?>
            <div class="category-card" onclick="window.location.href='<?php echo $details['link']; ?>'">
                <img src="<?php echo $details['image']; ?>" alt="<?php echo $name; ?>">
                <div class="category-name"><?php echo $name; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About Closetly</h3>
            <p>Your premier destination for fashion-forward clothing and accessories. We bring you the latest trends with uncompromising quality.</p>
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
