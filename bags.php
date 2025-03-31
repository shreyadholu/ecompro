<?php
require 'db.php'; // Include database connection

$sql = "SELECT id, name, price, image, product_id FROM bags";
$result = $conn->query($sql);
$bags = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bags[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly - Bags Collection</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

        .navbar .nav-items .form-control {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            width: 250px;
            font-size: 16px;
        }

        .catalog-container {
            padding: 60px 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: none;
            padding: 0;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .product-price {
            font-size: 20px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .add-to-cart {
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-to-cart:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 60px 40px 30px;
            margin-top: 60px;
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
            <form action="search.php" method="GET" class="d-flex">
                <input class="form-control" type="search" name="query" placeholder="Search..." required>
            </form>
        </div>
    </div>

    <!-- Product Catalog -->
    <div class="catalog-container">
        <h2 class="section-title">Bags Collection</h2>
        <div class="row g-4">
            <?php foreach ($bags as $bag): ?>
                <div class="col-md-3">
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($bag['image']); ?>" alt="<?php echo htmlspecialchars($bag['name']); ?>">
                        <div class="product-info">
                            <h5 class="product-name"><?php echo htmlspecialchars($bag['name']); ?></h5>
                            <p class="product-price">₹<?php echo htmlspecialchars($bag['price']); ?></p>
                            <form method="post" action="cart_system.php">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($bag['product_id']); ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($bag['name']); ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($bag['price']); ?>">
                                <input type="hidden" name="image" value="<?= htmlspecialchars($bag['image']); ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart">
                                    <i class="bi bi-bag-plus"></i> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
