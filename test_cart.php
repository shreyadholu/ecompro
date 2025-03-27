<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

$product_id = "TEST123";
$name = "Test Product";
$price = 500;
$image = "test.jpg";

// Try inserting into the cart
$insertQuery = $conn->prepare("INSERT INTO cart (product_id, name, price, image, quantity, total_price) VALUES (?, ?, ?, ?, 1, ?)");
$insertQuery->bind_param("ssdss", $product_id, $name, $price, $image, $price);
if ($insertQuery->execute()) {
    echo "✅ Test product added to cart!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
