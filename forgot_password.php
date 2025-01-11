<?php
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
        // Generate a unique token and save it in the database
        $token = bin2hex(random_bytes(50)); // Generate a secure random token
        $query = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send a reset link to the user's email (this is just an example)
        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
        mail($email, "Password Reset Request", "Click here to reset your password: $reset_link");

        echo "<p>Check your email for a password reset link.</p>";
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

            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </div>
</body>
</html>
