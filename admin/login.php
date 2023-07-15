<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
}
require_once '../db.php';
require_once 'functions.php';
require_once '../func.php';

$ip_address = $_SERVER['REMOTE_ADDR'];

if (isIpBlocked($conn, $ip_address)) {
  // Redirect to a page informing them they are blocked, or elsewhere
  header('Location: ../blocked.php');
  exit();
}

$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the query to fetch the user by username
    $stmt = $conn->prepare('SELECT * FROM `users` WHERE `username` = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $user_id = $_SESSION['user_id']; // Get the user_id from the session

            eventLog($conn, "Successful login", 'access', $user_id);

            header('Location: dashboard.php');
            exit();
        }
    }

    $error = 'Invalid username or password.';

    eventLog($conn, "Failed login attempt with username: $username", 'access');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="login-container">
        <img src="../logos/favicon.png" alt="Logo" class="logo"> <!-- Logo Image -->
        <form action="login.php" method="post" class="login-form">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" name="login" value="Login" class="login-btn">
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>