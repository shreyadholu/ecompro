<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if username or email already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or email already exists.";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        if ($stmt->execute()) {
            header('Location: homepage.php');
            exit();
        } else {
            $error = "Error registering user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Closetly</title>
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

        .register-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 500px;
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
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
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

        .register-btn {
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

        .register-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
        }

        .login-text {
            text-align: center;
            margin-top: 25px;
            color: #7f8c8d;
        }

        .login-text a {
            color: #2c3e50;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-text a:hover {
            color: #34495e;
            text-decoration: underline;
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

        .password-requirements {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <a href="homepage.php" class="logo">Closetly</a>
        <h1>Create an Account</h1>

        <?php if (isset($error)) { ?>
            <div class="error">
                <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="username" name="username" required>
                    <i class="bi bi-person"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="email" name="email" required>
                    <i class="bi bi-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <i class="bi bi-lock"></i>
                </div>
                <div class="password-requirements">
                    Password must be at least 8 characters long
                </div>
            </div>

            <button type="submit" class="register-btn">Create Account</button>
        </form>

        <div class="login-text">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
