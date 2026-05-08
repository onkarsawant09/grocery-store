<?php
require_once 'config.php';
if (!isLoggedIn()) redirect('login.php');
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
    $stmt->execute();
    header("Location: profile.php?updated=1");
    exit;
}
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
$page_title = "Edit Profile";
require_once 'header.php';
?>
<div class="container mt-5">
    <div class="auth-card">
        <h2>Edit Profile</h2>
        <form method="post">
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" placeholder="Phone">
            <textarea name="address" placeholder="Your address"><?= htmlspecialchars($user['address']) ?></textarea>
            <button type="submit" class="btn">Save Changes</button>
        </form>
        <p><a href="profile.php">Cancel</a></p>
    </div>
</div>
<?php require_once 'footer.php'; ?>