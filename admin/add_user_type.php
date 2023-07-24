<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $language_id = $_POST['language_id'];

    $logo_left = $_FILES['logo_left'];
    $logo_right = $_FILES['logo_right'];
    $watermark = $_FILES['watermark'];

    $logo_left_path = !empty($logo_left['name']) ? 'logos/' . time() . '_' . $logo_left['name'] : NULL;
    $logo_right_path = !empty($logo_right['name']) ? 'logos/' . time() . '_' . $logo_right['name'] : NULL;
    $watermark_path = !empty($watermark['name']) ? 'watermarks/' . time() . '_' . $watermark['name'] : NULL;

    if ($logo_left_path) {
        move_uploaded_file($logo_left['tmp_name'], '../' . $logo_left_path);
    }
    if ($logo_right_path) {
        move_uploaded_file($logo_right['tmp_name'], '../' . $logo_right_path);
    }
    if ($watermark_path) {
        move_uploaded_file($watermark['tmp_name'], '../' . $watermark_path);
    }

    $insert_query = "INSERT INTO user_type (name, title, logo_left, logo_right, watermark, language_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'sssssi', $name, $title, $logo_left_path, $logo_right_path, $watermark_path, $language_id);
    mysqli_stmt_execute($stmt);

    eventLog($conn, "User type added", 'addition', $user_id);

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
            <label for="language_id">Language:</label>
            <select name="language_id" id="language_id" required>
                <?php while ($language = mysqli_fetch_assoc($languages_result)) { ?>
                    <option value="<?php echo $language['id']; ?>"><?php echo $language['language']; ?></option>
                <?php } ?>
            </select>
            <br>
            <label for="logo_left">Logo Left:</label>
            <input type="file" name="logo_left" id="logo_left">
            <br>
            <label for="logo_right">Logo Right:</label>
            <input type="file" name="logo_right" id="logo_right">
            <br>
            <label for="watermark">Watermark:</label>
            <input type="file" name="watermark" id="watermark">
            <br>
            <button type="submit">Add User Type</button>
        </form>
    </div>
</body>
</html>