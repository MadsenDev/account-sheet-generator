<?php
session_start();

require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to dashboard");
    
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session
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