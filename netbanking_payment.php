<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

// Load Razorpay configuration
$razorpay_config = require 'config/razorpay-config.php';
$order_id = $_GET['order_id'];

// Initialize Razorpay API
$api = new Api($razorpay_config['key_id'], $razorpay_config['key_secret']);

// Fetch order details
$orderQuery = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: checkout_page.php");
    exit();
}

// Create Razorpay Order if not already created
if (!isset($_SESSION['razorpay_order_id'])) {
    $orderData = [
        'receipt'         => 'rcpt_' . $order_id,
        'amount'          => $order['total_amount'] * 100,
        'currency'        => 'INR',
        'payment_capture' => 1
    ];
    
    $razorpayOrder = $api->order->create($orderData);
    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment - Closetly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .payment-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .payment-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Complete Your Payment</h2>
        <p>Amount to Pay: ₹<?php echo number_format($order['total_amount'], 2); ?></p>
        <button id="pay-btn" class="payment-btn">Pay Now</button>
    </div>

    <script>
        var options = {
            "key": "<?php echo $razorpay_config['key_id']; ?>",
            "amount": "<?php echo $order['total_amount'] * 100; ?>",
            "currency": "INR",
            "name": "Closetly",
            "description": "Order Payment",
            "image": "your-logo-url.png",
            "order_id": "<?php echo $_SESSION['razorpay_order_id']; ?>",
            "handler": function (response){
                window.location.href = "verify_payment.php?payment_id=" + response.razorpay_payment_id + 
                    "&order_id=<?php echo $order_id; ?>";
            },
            "prefill": {
                "name": "<?php echo htmlspecialchars($order['full_name']); ?>",
                "contact": "<?php echo htmlspecialchars($order['contact_number']); ?>"
            },
            "theme": {
                "color": "#2c3e50"
            }
        };
        var rzp = new Razorpay(options);
        document.getElementById('pay-btn').onclick = function(e){
            rzp.open();
            e.preventDefault();
        }
    </script>
</body>
</html>