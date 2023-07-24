<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $title = $_POST['title'];
    $language_id = $_POST['language_id'];

    $logo_left = $_FILES['logo_left'];
    $logo_right = $_FILES['logo_right'];
    $watermark = $_FILES['watermark'];

    $logo_left_path = !empty($logo_left['name']) ? 'logos/' . time() . '_' . $logo_left['name'] : $user_type['logo_left'];
    $logo_right_path = !empty($logo_right['name']) ? 'logos/' . time() . '_' . $logo_right['name'] : $user_type['logo_right'];
    $watermark_path = !empty($watermark['name']) ? 'watermarks/' . time() . '_' . $watermark['name'] : $user_type['watermark'];

    if ($logo_left_path && $logo_left['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($logo_left['tmp_name'], '../' . $logo_left_path);
    }
    if ($logo_right_path && $logo_right['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($logo_right['tmp_name'], '../' . $logo_right_path);
    }
    if ($watermark_path && $watermark['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($watermark['tmp_name'], '../' . $watermark_path);
    }

    $update_query = "UPDATE user_type SET name = ?, title = ?, logo_left = ?, logo_right = ?, watermark = ?, language_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $title, $logo_left_path, $logo_right_path, $watermark_path, $language_id, $id);
    mysqli_stmt_execute($stmt);

    eventLog($conn, "User type updated", 'modification', $user_id);

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

$languages_query = "SELECT id, language FROM languages";
$languages_result = mysqli_query($conn, $languages_query);
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
            <label for="language_id">Language:</label>
            <select name="language_id" id="language_id" required>
                <?php while ($language = mysqli_fetch_assoc($languages_result)) { ?>
                    <option value="<?php echo $language['id']; ?>" <?php if ($language['id'] == $user_type['language_id']) { echo 'selected'; } ?>><?php echo $language['language']; ?></option>
                <?php } ?>
            </select>
            <br>
            <label for="logo_left">Logo Left (current: <?php echo $user_type['logo_left']; ?>):</label>
            <input type="file" name="logo_left" id="logo_left">
            <br>
            <label for="logo_right">Logo Right (current: <?php echo $user_type['logo_right']; ?>):</label>
            <input type="file" name="logo_right" id="logo_right">
            <br>
            <label for="watermark">Watermark (current: <?php echo $user_type['watermark']; ?>):</label>
            <input type="file" name="watermark" id="watermark">
            <br>
            <button type="submit">Update User Type</button>
        </form>
    </div>
</body>
</html>