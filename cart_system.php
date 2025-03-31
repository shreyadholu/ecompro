<?php
session_start();
require 'db.php';
$categories = ['bags', 'footwear', 'bottomwear', 'dresses', 'topss'];
$products = [];
foreach ($categories as $category) {
    $sql = "SELECT id, name, price, image, product_id FROM $category";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $checkQuery = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;
        $new_total_price = $new_quantity * $price;
        $updateQuery = "UPDATE cart SET quantity = $new_quantity, total_price = $new_total_price WHERE product_id = '$product_id'";
        $conn->query($updateQuery);
    } else {
        $insertQuery = "INSERT INTO cart (product_id, name, price, image, quantity, total_price) 
                        VALUES ('$product_id', '$name', '$price', '$image', 1, '$price')";
        $conn->query($insertQuery);
    }
    header("Location: cart_system.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action == "increase") {
        $query = $conn->prepare("UPDATE cart SET quantity = quantity + 1, total_price = (quantity + 1) * price WHERE product_id = ?");
    } else {
        $query = $conn->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1), total_price = GREATEST((quantity - 1), 1) * price WHERE product_id = ?");
    }
    $query->bind_param("s", $product_id);
    $query->execute();
    header("Location: cart_system.php");
    exit();
}
// Handle Remove from Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $deleteQuery = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
    $deleteQuery->bind_param("s", $product_id);
    $deleteQuery->execute();
    header("Location: cart_system.php");
    exit();
}
// Fetch Cart Items
$cartQuery = "SELECT * FROM cart";
$cartResult = $conn->query($cartQuery);

if (!$cartResult) {
    die("Query failed: " . $conn->error);
}
$cart_items = $cartResult->fetch_all(MYSQLI_ASSOC);
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['total_price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly - Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px 40px;
        }

        .navbar .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }

        .cart-container {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
        }

        .cart-title {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
        }

        .cart-table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #2c3e50;
            color: white;
        }

        .table th {
            font-weight: 500;
            padding: 15px;
            border: none;
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: none;
            border: 2px solid #2c3e50;
            color: #2c3e50;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background-color: #2c3e50;
            color: white;
        }

        .remove-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-price {
            font-size: 24px;
            color: #2c3e50;
            font-weight: 600;
        }

        .checkout-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .empty-cart {
            text-align: center;
            padding: 40px 0;
            color: #7f8c8d;
        }

        .empty-cart i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .continue-shopping {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Closetly</div>
        <a href="homepage.php" class="continue-shopping">
            <i class="bi bi-arrow-left"></i> Continue Shopping
        </a>
    </nav>

    <div class="cart-container">
        <h2 class="cart-title">Your Shopping Cart</h2>
        <?php if (count($cart_items) > 0): ?>
            <div class="cart-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= htmlspecialchars($item['image']); ?>" class="product-img">
                                    <span class="product-name"><?= htmlspecialchars($item['name']); ?></span>
                                </div>
                            </td>
                            <td>₹<?= htmlspecialchars($item['price']); ?></td>
                            <td>
                                <div class="quantity-controls">
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']); ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" name="update_cart" class="quantity-btn">-</button>
                                    </form>
                                    <span><?= htmlspecialchars($item['quantity']); ?></span>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']); ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" name="update_cart" class="quantity-btn">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>₹<?= htmlspecialchars($item['total_price']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']); ?>">
                                    <button type="submit" name="remove_from_cart" class="remove-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-section">
                <div class="total-price">
                    Total: ₹<?= number_format($total_price, 2); ?>
                </div>
                <button class="checkout-btn" onclick="window.location.href='checkout_page.php'">
                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="bi bi-cart-x"></i>
                <h3>Your cart is empty</h3>
                <p>Look like you haven't added anything to your cart yet.</p>
                <a href="homepage.php" class="checkout-btn">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

