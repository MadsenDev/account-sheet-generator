<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to edit user type");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $title = $_POST['title'];

    $logo_left = $_FILES['logo_left'];
    $logo_right = $_FILES['logo_right'];

    $logo_left_path = $logo_right_path = '';

    if (!empty($logo_left['name'])) {
        $logo_left_path = 'logos/' . time() . '_' . $logo_left['name'];
        move_uploaded_file($logo_left['tmp_name'], '../' . $logo_left_path);
    }

    if (!empty($logo_right['name'])) {
        $logo_right_path = 'logos/' . time() . '_' . $logo_right['name'];
        move_uploaded_file($logo_right['tmp_name'], '../' . $logo_right_path);
    }

    $update_query = "UPDATE user_type SET name = ?, title = ?" . (!empty($logo_left_path) ? ", logo_left = ?" : "") . (!empty($logo_right_path) ? ", logo_right = ?" : "") . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);

    if (!empty($logo_left_path) && !empty($logo_right_path)) {
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $title, $logo_left_path, $logo_right_path, $id);
    } elseif (!empty($logo_left_path)) {
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $title, $logo_left_path, $id);
    } elseif (!empty($logo_right_path)) {
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $title, $logo_right_path, $id);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $title, $id);
    }

    mysqli_stmt_execute($stmt);
    eventLog($conn, "User type updated", $user_id);

    header('Location: manage_user_type.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage_user_type.php');
    exit();
}

$user_type_id = $_GET['id'];
$user_type_query = "SELECT * FROM user_type WHERE id = ?";
$stmt = mysqli_prepare($conn, $user_type_query);
mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_type = mysqli_fetch_assoc($result);

if (!$user_type) {
    header('Location: manage_user_type.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Type</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit User Type</h1>
        <a href="manage_user_type.php">Back to Manage User Types</a>
        <form action="edit_user_type.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user_type['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $user_type['name']; ?>" required>
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $user_type['title']; ?>" required>
            <br>
            <label for="logo_left">Logo Left (current: <?php echo $user_type['logo_left']; ?>):</label>
            <input type="file" name="logo_left" id="logo_left">
            <br>
            <label for="logo_right">Logo Right (current: <?php echo $user_type['logo_right']; ?>):</label>
            <input type="file" name="logo_right" id="logo_right">
            <br>
            <button type="submit">Update User Type</button>
        </form>
    </div>
</body>
</html>