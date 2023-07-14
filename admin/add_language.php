<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert the new language
    $language = $_POST['language'];
    $insert_query = "INSERT INTO languages (language) VALUES (?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 's', $language);
    mysqli_stmt_execute($stmt);
    eventLog($conn, "Language added: $language", 'addition', $user_id);
    header('Location: manage_languages.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Language</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add Language</h1>
        <a href="manage_languages.php">Back to Manage Languages</a>
        <form action="add_language.php" method="POST">
            <label for="language">Language:</label>
            <input type="text" id="language" name="language" required>
            <button type="submit">Add Language</button>
        </form>
    </div>
</body>
</html>