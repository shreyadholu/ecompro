<?php
session_start();

if (isset($_GET['index'])) {
    $index = $_GET['index'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
}

header("Location: cart_page.php");
exit();
?>
