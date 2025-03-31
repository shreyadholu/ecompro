<?php
session_start();
require 'db.php';

if (!isset($_GET['order_id'])) {
    header("Location: homepage.php");
    exit();
}

$order_id = $conn->real_escape_string($_GET['order_id']);

// Fetch order details
$orderQuery = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Fetch order items
$itemsQuery = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($itemsQuery);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Closetly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
        }

        .confirmation-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .success-icon {
            text-align: center;
            margin-bottom: 30px;
        }

        .success-icon i {
            font-size: 80px;
            color: #2ecc71;
        }

        .order-title {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin-bottom: 40px;
        }

        .order-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .order-items {
            margin: 30px 0;
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background: #2c3e50;
            color: white;
            font-weight: 500;
        }

        .delivery-info {
            background: #e8f6ff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .delivery-info i {
            margin-right: 10px;
            color: #3498db;
        }

        .continue-shopping {
            text-align: center;
            margin-top: 30px;
        }

        .btn-shopping {
            background: #2c3e50;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-shopping:hover {
            background: #34495e;
            transform: translateY(-2px);
            color: white;
        }

        .thank-you {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        
        <div class="order-title">
            <h1>Order Confirmed!</h1>
            <p>Your order has been successfully placed</p>
        </div>

        <div class="order-details">
            <h3>Order Details</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order ID:</strong> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Payment Method:</strong> Cash on Delivery</p>
                    <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
            </div>
        </div>

        <div class="delivery-info">
            <h3>Delivery Information</h3>
            <p><i class="bi bi-person"></i><?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><i class="bi bi-telephone"></i><?php echo htmlspecialchars($order['contact_number']); ?></p>
            <p><i class="bi bi-geo-alt"></i><?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <p><i class="bi bi-truck"></i>Expected Delivery: <?php echo date('d M Y', strtotime($order['expected_delivery'])); ?></p>
        </div>

        <div class="order-items">
            <h3>Order Summary</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td>₹<?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                        <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="thank-you">
            <p>Thank you for shopping with Closetly!</p>
            <p>You will receive an email confirmation shortly.</p>
        </div>

        <div class="continue-shopping">
            <a href="homepage.php" class="btn-shopping">
                <i class="bi bi-arrow-left"></i> Continue Shopping
            </a>
        </div>
    </div>
</body>
</html>