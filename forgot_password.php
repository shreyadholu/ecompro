<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed using Composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';

    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Generate a random password
        function generateRandomPassword($length = 8) {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
            $password = '';
            $charactersLength = strlen($characters);

            for ($i = 0; $i < $length; $i++) {
                $password .= $characters[rand(0, $charactersLength - 1)];
            }

            return $password;
        }

        $newPassword = generateRandomPassword();
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the user's password in the database
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Send the new password via PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Mail server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.your-email-provider.com'; // e.g., smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; // Your email
            $mail->Password = 'your-email-password'; // Your email password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email settings
            $mail->setFrom('your-email@example.com', 'Your Name');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Your New Password";
            $mail->Body = "
            <html>
            <head>
                <title>Your New Password</title>
            </head>
            <body style='font-family: Arial, sans-serif; background: #d9c2ba; padding: 20px;'>
                <div style='max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h1 style='color: #333;'>Your New Password</h1>
                    <p>Dear User,</p>
                    <p>Your new password is: <strong style='color: #4b3b42;'>$newPassword</strong></p>
                    <p>Please log in using this password and change it for better security.</p>
                    <p style='margin-top: 20px; font-size: 14px; color: #555;'>Regards,<br>Your Team</p>
                </div>
            </body>
            </html>
            ";

            $mail->send();
            echo "<p>Email sent successfully with your new password.</p>";
        } catch (Exception $e) {
            echo "<p>Failed to send email. Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>No account found with that email address.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #d9c2ba;
        }
        .reset-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #9c8481;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #4b3b42;
        }
        a {
            color: #4b3b42;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Forgot Your Password?</h1>
        <form method="POST" action="forgot_password.php">
            <label for="email">Enter your email address:</label>
            <input type="email" name="email" required>

            <button type="submit">Send New Password</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </div>
</body>
</html>
