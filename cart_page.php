<?php
require 'cart_database.php'; // Include the database-based cart functions

// Handle quantity updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["index"])) {
    $product_id = $_POST["index"];

    if (isset($_POST["increase"])) {
        updateCartQuantity($product_id, 'increase');
    } elseif (isset($_POST["decrease"])) {
        updateCartQuantity($product_id, 'decrease');
    }
    header("Location: cart_page.php");
    exit();
}

// Fetch cart items from database
$cartItems = getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: white; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: white; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .container { margin-top: 20px; }
        .cart-item { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .cart-item img { width: 80px; height: 80px; object-fit: contain; border-radius: 5px; }
        .cart-total { font-size: 20px; font-weight: bold; }
        .remove-item { color: red; cursor: pointer; }
        .checkout-btn { margin-top: 20px; background-color: green; color: white; padding: 10px; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; }
        .quantity-btn { background: none; border: none; font-size: 18px; cursor: pointer; margin: 0 5px; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Closetly</div>
    <div class="nav-items">
        <button onclick="window.location.href='homepage.php'">Home</button>
        <button onclick="window.location.href='shop.php'">Shop</button>
        <button onclick="window.location.href='cart_page.php'">Cart (<?php echo count($cartItems); ?>)</button>
    </div>
</div>

<div class="container">
    <h2 class="text-center">Shopping Cart</h2>
    
    <?php if (count($cartItems) > 0): ?>
        <div class="cart-items">
            <?php $total = 0; ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div>
                        <h5><?php echo $item['name']; ?></h5>
                        <p>₹<?php echo $item['price']; ?> x <?php echo $item['quantity']; ?></p>
                    </div>

                    <div>
                        <!-- Quantity update buttons -->
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="index" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="decrease" class="quantity-btn">−</button>
                        </form>
                        <span><?php echo $item['quantity']; ?></span>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="index" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" name="increase" class="quantity-btn">+</button>
                        </form>
                    </div>
                </div>
                <?php $total += ($item['price'] * $item['quantity']); ?>
            <?php endforeach; ?>
        </div>

        <h3 class="cart-total">Total: ₹<?php echo $total; ?></h3>
        <button class="checkout-btn">Proceed to Checkout</button>
    
    <?php else: ?>
        <p class="text-center">Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
