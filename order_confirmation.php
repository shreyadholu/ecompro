<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

if (!isset($_GET['order_id'])) {
    header("Location: homepage.php");
    exit();
}

$order_id = $conn->real_escape_string($_GET['order_id']);

// Load Razorpay configuration
$razorpay_config = require 'config/razorpay-config.php';

if (!isset($razorpay_config['key_secret'])) {
    error_log('Razorpay secret key not found in config');
    die('Payment configuration error');
}

// Initialize Razorpay with config values
$api = new Api($razorpay_config['key_id'], $razorpay_config['key_secret']);

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

// Create Razorpay Order
$orderData = [
    'receipt'         => 'rcpt_' . time(),
    'amount'          => $order['total_amount'] * 100, // Convert to paise
    'currency'        => 'INR',
    'payment_capture' => 1
];

$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... existing form handling code ...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Closetly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .confirmation-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .order-success {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-success i {
            font-size: 64px;
            color: #2ecc71;
            margin-bottom: 20px;
        }

        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .order-items {
            margin-top: 30px;
        }

        .delivery-info {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .home-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .home-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
            color: white;
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="confirmation-container">
        <div class="order-success">
            <i class="bi bi-check-circle-fill"></i>
            <h1>Order Confirmed!</h1>
            <p>Thank you for shopping with us.</p>
        </div>

        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact_number']); ?></p>
            <p><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></p>
        </div>

        <div class="order-items">
            <h3>Order Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td>₹<?php echo number_format($item['total_price'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                        <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="delivery-info">
            <h3>Delivery Information</h3>
            <p><i class="bi bi-truck"></i> Expected Delivery: <?php echo date('d M Y', strtotime($order['expected_delivery'])); ?></p>
            <p>Payment Method: Cash on Delivery</p>
            <p>Amount to be paid: ₹<?php echo number_format($order['total_amount'], 2); ?></p>
        </div>

        <div class="text-center">
            <a href="homepage.php" class="home-btn">Continue Shopping</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var options = {
            "key": "<?php echo $razorpay_config['key_id']; ?>", // Use key_id instead of direct value
            "amount": "<?php echo $order['total_amount'] * 100; ?>",
            "currency": "INR",
            "name": "Closetly",
            "description": "Order Payment",
            "image": "your-logo-url.png",
            "order_id": "<?php echo $razorpayOrderId; ?>",
            "handler": function (response){
                window.location.href = "verify_payment.php?payment_id=" + response.razorpay_payment_id + 
                    "&order_id=<?php echo $order_id; ?>&razorpay_order_id=" + response.razorpay_order_id +
                    "&razorpay_signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "<?php echo htmlspecialchars($order['full_name']); ?>",
                "contact": "<?php echo htmlspecialchars($order['contact_number']); ?>"
            },
            "theme": {
                "color": "#2c3e50"
            }
        };

        document.getElementById('checkout-btn').onclick = function(e){
            e.preventDefault();
            var rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
</body>
</html>