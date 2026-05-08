<?php
session_start();
require_once '../config.php'; // for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check against database
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $row['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .auth-card h2 {
            color: var(--admin-green, #2e7d32);
            margin-bottom: 25px;
            text-align: center;
        }
        .auth-card input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .auth-card button {
            width: 100%;
            padding: 12px;
            background: var(--admin-green, #2e7d32);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .auth-card button:hover {
            background: #1b5e20;
        }
        .error {
            color: #dc3545;
            text-align: center;
            margin-top: 10px;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .register-link a {
            color: #2e7d32;
            text-decoration: none;
        }
        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>Admin Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
        <div class="register-link">
            Don't have an admin account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>