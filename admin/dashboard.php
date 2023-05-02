<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
    </div>
</body>
</html>