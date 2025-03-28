<?php
session_start();
require 'db.php';

// Get cart total
$cartQuery = "SELECT SUM(total_price) as total FROM cart";
$result = $conn->query($cartQuery);
$total = $result->fetch_assoc()['total'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $order_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO orders (full_name, contact_number, shipping_address, payment_method, order_date, total_amount) 
            VALUES ('$name', '$contact', '$address', '$payment_method', '$order_date', '$total')";

    if ($conn->query($sql)) {
        // Clear cart after successful order
        $conn->query("DELETE FROM cart");
        header("Location: order_confirmation.php?order_id=" . $conn->insert_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly - Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .checkout-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .checkout-title {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .submit-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .order-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1 class="checkout-title">Checkout</h1>
        
        <div class="order-summary">
            <div class="total-amount">
                Total Amount: ₹<?php echo number_format($total, 2); ?>
            </div>
        </div>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact" name="contact" 
                       pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Shipping Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="">Select payment method</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="upi">UPI</option>
                    <option value="netbanking">Net Banking</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Place Order</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>