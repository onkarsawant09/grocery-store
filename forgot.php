<?php
require_once 'config.php';
$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        // In real app, send email. For demo, just show message.
        $success = "Password reset link sent to your email (demo). <a href='login.php'>Back to Login</a>";
    } else {
        $error = "Email not found!";
    }
}
$page_title = "Forgot Password";
require_once 'header.php';
?>
<div class="container mt-5">
    <div class="auth-card">
        <h2>Forgot Password</h2>
        <?php if($success): ?>
            <div class="success"><?= $success ?></div>
        <?php else: ?>
            <form method="post">
                <input type="email" name="email" placeholder="Registered Email" required>
                <button type="submit" class="btn">Send Reset Link</button>
                <?php if($error) echo "<p class='error'>$error</p>"; ?>
            </form>
            <p><a href="login.php">Back to Login</a></p>
        <?php endif; ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>