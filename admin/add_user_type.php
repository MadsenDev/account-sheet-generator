<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $title = $_POST['title'];

    $logo_left = $_FILES['logo_left'];
    $logo_right = $_FILES['logo_right'];

    $logo_left_path = !empty($logo_left['name']) ? 'logos/' . time() . '_' . $logo_left['name'] : NULL;
    $logo_right_path = !empty($logo_right['name']) ? 'logos/' . time() . '_' . $logo_right['name'] : NULL;

    if ($logo_left_path) {
        move_uploaded_file($logo_left['tmp_name'], '../' . $logo_left_path);
    }
    if ($logo_right_path) {
        move_uploaded_file($logo_right['tmp_name'], '../' . $logo_right_path);
    }

    $insert_query = "INSERT INTO user_type (name, title, logo_left, logo_right) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $title, $logo_left_path, $logo_right_path);
    mysqli_stmt_execute($stmt);

    header('Location: manage_user_type.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Type</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add User Type</h1>
        <a href="manage_user_type.php">Back to Manage User Types</a>
        <form action="add_user_type.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <br>
            <label for="logo_left">Logo Left:</label>
            <input type="file" name="logo_left" id="logo_left">
            <br>
            <label for="logo_right">Logo Right:</label>
            <input type="file" name="logo_right" id="logo_right">
            <br>
            <button type="submit">Add User Type</button>
        </form>
    </div>
</body>
</html>