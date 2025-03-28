<?php
require 'vendor/autoload.php';
require 'db.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$keyId = 'YOUR_KEY_ID';
$keySecret = 'YOUR_KEY_SECRET';

$success = false;

if (!empty($_POST['razorpay_payment_id']) && !empty($_POST['razorpay_order_id']) && !empty($_POST['razorpay_signature'])) {
    $api = new Api($keyId, $keySecret);

    try {
        $attributes = array(
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_order_id' => $_POST['razorpay_order_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
        $success = true;
    } catch(SignatureVerificationError $e) {
        $success = false;
        error_log('Razorpay Error : ' . $e->getMessage());
    }
}

if ($success) {
    // Update order status in database
    $order_id = $_POST['order_id'];
    $payment_id = $_POST['razorpay_payment_id'];
    
    $sql = "UPDATE orders SET 
            payment_status = 'completed',
            payment_id = '$payment_id'
            WHERE id = '$order_id'";
            
    $conn->query($sql);
    
    header("Location: order_confirmation.php?order_id=$order_id");
} else {
    header("Location: checkout_page.php?payment=failed");
}
?>