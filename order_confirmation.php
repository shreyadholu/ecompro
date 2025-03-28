<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

// Initialize Razorpay
$keyId = 'YOUR_KEY_ID';
$keySecret = 'YOUR_KEY_SECRET';
$api = new Api($keyId, $keySecret);

// Get cart total
$cartQuery = "SELECT SUM(total_price) as total FROM cart";
$result = $conn->query($cartQuery);
$total = $result->fetch_assoc()['total'];

// Create Razorpay Order
$orderData = [
    'receipt'         => 'rcpt_' . time(),
    'amount'          => $total * 100, // Convert to paise
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
    <!-- ... existing head content ... -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="checkout-container">
        <h1 class="checkout-title">Checkout</h1>
        
        <div class="order-summary">
            <div class="total-amount">
                Total Amount: ₹<?php echo number_format($total, 2); ?>
            </div>
        </div>

        <form method="POST" action="" id="checkout-form">
            <!-- ... existing form fields ... -->

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="">Select payment method</option>
                    <option value="razorpay">Pay with Razorpay</option>
                    <option value="cod">Cash on Delivery</option>
                </select>
            </div>

            <button type="submit" class="submit-btn" id="checkout-btn">Place Order</button>
        </form>
    </div>

    <script>
        var options = {
            "key": "<?php echo $keyId; ?>",
            "amount": "<?php echo $total * 100; ?>",
            "currency": "INR",
            "name": "Closetly",
            "description": "Order Payment",
            "image": "your-logo-url.png",
            "order_id": "<?php echo $razorpayOrderId; ?>",
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('checkout-form').submit();
            },
            "prefill": {
                "name": document.getElementById('name').value,
                "contact": document.getElementById('contact').value
            },
            "theme": {
                "color": "#2c3e50"
            }
        };

        document.getElementById('checkout-btn').onclick = function(e){
            e.preventDefault();
            if(document.getElementById('payment_method').value === 'razorpay') {
                var rzp1 = new Razorpay(options);
                rzp1.open();
            } else {
                document.getElementById('checkout-form').submit();
            }
        };
    </script>
</body>
</html>