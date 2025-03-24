<?php
session_start();
require 'db.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $image = $_POST["image"];

    if ($user_id) {
        // If user is logged in, store in database
        $checkCart = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($checkCart);
        $stmt->bind_param("ii", $user_id, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $updateCart = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($updateCart);
            $stmt->bind_param("ii", $user_id, $id);
            $stmt->execute();
        } else {
            $insertCart = "INSERT INTO cart (user_id, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($insertCart);
            $stmt->bind_param("iisss", $user_id, $id, $name, $price, $image);
            $stmt->execute();
        }
    } else {
        // If user is not logged in, store in session
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        $found = false;
        foreach ($_SESSION["cart"] as &$item) {
            if ($item["id"] == $id) {
                $item["quantity"] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION["cart"][] = ["id" => $id, "name" => $name, "price" => $price, "image" => $image, "quantity" => 1];
        }
    }
    
    echo "success";
}
?>
