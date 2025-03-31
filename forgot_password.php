<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        // Send the new password via email using the mail() function
        $to = $email;
        $subject = "Your New Password";
        $message = "
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

        // Headers to specify HTML content and proper encoding
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@yourdomain.com" . "\r\n";

        // Attempt to send the email
        if (mail($to, $subject, $message, $headers)) {
            // Show success message
            echo "<script>
                    alert('Email sent successfully with your new password.');
                    window.location.href = 'login.php'; // Redirect to login page
                  </script>";
        } else {
            // Show error message
            echo "<script>
                    alert('Failed to send email. Please try again later.');
                    window.location.href = 'forgot_password.php'; // Stay on the forgot password page
                  </script>";
        }
    } else {
        // Show error if email is not found in the database
        echo "<script>
                alert('No account found with that email address.');
                window.location.href = 'forgot_password.php'; // Stay on the forgot password page
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Closetly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .reset-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 450px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            text-decoration: none;
            display: block;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .description {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
        }

        .reset-btn {
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: 500;
            font-size: 16px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .reset-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .back-to-login {
            text-align: center;
            margin-top: 25px;
        }

        .back-to-login a {
            color: #2c3e50;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-to-login a:hover {
            color: #34495e;
        }

        .error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <a href="homepage.php" class="logo">Closetly</a>
        <h1>Forgot Your Password?</h1>
        <p class="description">Enter your email address and we'll send you instructions to reset your password.</p>

        <?php if (isset($error)) { ?>
            <div class="error">
                <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST" action="forgot_password.php">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Enter your email">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>

            <button type="submit" class="reset-btn">Send Reset Instructions</button>
        </form>

        <div class="back-to-login">
            <a href="login.php">
                <i class="bi bi-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
