<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';
use Razorpay\Api\Api;

// Get cart total and items with their respective categories
$cartQuery = "SELECT c.*, 
    CASE 
        WHEN t.name IS NOT NULL THEN t.name
        WHEN b.name IS NOT NULL THEN b.name
        WHEN d.name IS NOT NULL THEN d.name
        WHEN f.name IS NOT NULL THEN f.name
        WHEN bg.name IS NOT NULL THEN bg.name
    END as product_name
    FROM cart c 
    LEFT JOIN topss t ON c.product_id = t.product_id
    LEFT JOIN bottomwear b ON c.product_id = b.product_id
    LEFT JOIN dresses d ON c.product_id = d.product_id
    LEFT JOIN footwear f ON c.product_id = f.product_id
    LEFT JOIN bags bg ON c.product_id = bg.product_id";

$result = $conn->query($cartQuery);
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['total_price'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $order_date = date('Y-m-d H:i:s');
    $delivery_date = date('Y-m-d', strtotime('+5 days')); // Estimated delivery in 5 days

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert order
        $sql = "INSERT INTO orders (full_name, contact_number, shipping_address, payment_method, 
                order_date, expected_delivery, total_amount) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssd", $name, $contact, $address, $payment_method, 
                         $order_date, $delivery_date, $total);
        $stmt->execute();
        $order_id = $conn->insert_id;
        // Insert order items with category information
        $items_sql = "INSERT INTO order_items (order_id, product_id, product_name, 
                     quantity, price, total_price, category) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $items_stmt = $conn->prepare($items_sql);

        foreach ($cart_items as $item) {
            // Determine product category
            $category = '';
            if (strpos($item['product_id'], 'TOP') !== false) {
                $category = 'Tops';
            } elseif (strpos($item['product_id'], 'BTM') !== false) {
                $category = 'Bottomwear';
            } elseif (strpos($item['product_id'], 'DRS') !== false) {
                $category = 'Dresses';
            } elseif (strpos($item['product_id'], 'FTW') !== false) {
                $category = 'Footwear';
            } elseif (strpos($item['product_id'], 'BAG') !== false) {
                $category = 'Bags';
            }

            $items_stmt->bind_param("iisidss", 
                $order_id,
                $item['product_id'],
                $item['product_name'],
                $item['quantity'],
                $item['price'],
                $item['total_price'],
                $category
            );
            $items_stmt->execute();
        }

        // Clear cart
        $conn->query("DELETE FROM cart");

        // Commit transaction
        $conn->commit();

        // Razorpay integration for netbanking
        if ($payment_method === 'netbanking') {
            $api = new Api('rzp_test_XCs0E3IXY05Ek0', 'your_secret_key_here');
            // Fetch the secret key from an environment variable
            $secretKey = getenv('RAZORPAY_SECRET_KEY');
            if (!$secretKey) {
                throw new Exception("Razorpay secret key is not set in the environment variables.");
            }

            $api = new Api('rzp_test_XCs0E3IXY05Ek0', $secretKey);            
            $razorpayOrder = $api->order->create([
                'receipt' => 'order_' . $order_id,
                'amount' => $total * 100, // Amount in paise
                'currency' => 'INR'
            ]);
            
            $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];
            $_SESSION['order_id'] = $order_id;
        }

        // Redirect based on payment method
        if ($payment_method === 'cod') {
            header("Location: cod_confirmation.php?order_id=" . $order_id);
        } elseif ($payment_method === 'netbanking') {
            header("Location: netbanking_payment.php?order_id=" . $order_id);
        } else {
            header("Location: process_payment.php?order_id=" . $order_id);
        }
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
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