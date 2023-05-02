<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $insert_query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);

    header('Location: manage_categories.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add Category</h1>
        <a href="manage_categories.php">Back to Manage Categories</a>
        <form action="add_category.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>