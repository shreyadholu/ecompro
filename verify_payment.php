<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

// Load Razorpay configuration
$razorpay_config = require 'config/razorpay-config.php';

$success = false;
$error = "Payment Failed";

if (!empty($_GET['payment_id']) && !empty($_GET['order_id']) && !empty($_GET['razorpay_signature'])) {
    $api = new Api($razorpay_config['key_id'], $razorpay_config['key_secret']);
    
    try {
        // Verify payment signature
        $attributes = array(
            'razorpay_payment_id' => $_GET['payment_id'],
            'razorpay_order_id'   => $_GET['razorpay_order_id'],
            'razorpay_signature'  => $_GET['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
        
        // Fetch payment details
        $payment = $api->payment->fetch($_GET['payment_id']);
        
        if ($payment['status'] === 'captured') {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Update order status
                $sql = "UPDATE orders SET 
                        payment_status = 'completed',
                        payment_id = ?,
                        razorpay_order_id = ?,
                        razorpay_payment_id = ?,
                        razorpay_signature = ?
                        WHERE id = ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", 
                    $_GET['payment_id'],
                    $_GET['razorpay_order_id'],
                    $_GET['payment_id'],
                    $_GET['razorpay_signature'],
                    $_GET['order_id']
                );
                $stmt->execute();

                // Clear cart after successful payment
                $conn->query("DELETE FROM cart");
                
                // Commit transaction
                $conn->commit();
                
                header("Location: payment_success.php?order_id=" . $_GET['order_id']);
                exit();
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                throw $e;
            }
        }
    } catch (SignatureVerificationError $e) {
        $error = "Payment signature verification failed";
        error_log("Razorpay Signature Verification Error: " . $e->getMessage());
        header("Location: payment_failed.php?error=" . urlencode($error));
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Razorpay Payment Error: " . $e->getMessage());
        header("Location: payment_failed.php?error=" . urlencode($error));
        exit();
    }
}

header("Location: payment_failed.php");
exit();
?>