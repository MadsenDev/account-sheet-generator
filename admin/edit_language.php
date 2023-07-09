<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to edit language");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$language_id = $_GET['id']; // Get the id of the language to edit

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the language
    $language = $_POST['language'];
    $update_query = "UPDATE languages SET language = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'si', $language, $language_id);
    mysqli_stmt_execute($stmt);
    header('Location: manage_languages.php');
    exit();
} else {
    // Fetch the language to edit
    $select_query = "SELECT * FROM languages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, 'i', $language_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $language = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Language</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Language</h1>
        <a href="manage_languages.php">Back to Manage Languages</a>
        <form action="edit_language.php?id=<?php echo $language_id; ?>" method="POST">
            <label for="language">Language:</label>
            <input type="text" id="language" name="language" required value="<?php echo $language['language']; ?>">
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>