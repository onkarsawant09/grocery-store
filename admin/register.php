<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username already exists
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors[] = "Username already taken!";
        }
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            // Auto login after registration
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $conn->insert_id;
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Registration failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
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
            font-size: 14px;
        }
        .error-list {
            color: #dc3545;
            margin-bottom: 15px;
            padding-left: 20px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .login-link a {
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
        <h2>Admin Registration</h2>
        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <?php foreach($errors as $err): ?>
                    <div>• <?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username (min 3 chars)" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            <input type="password" name="password" placeholder="Password (min 4 chars)" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>