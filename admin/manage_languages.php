<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

// Fetch all languages from the database
$languages_query = "SELECT * FROM languages";
$languages_result = mysqli_query($conn, $languages_query);
$languages = mysqli_fetch_all($languages_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Languages</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Languages <a href="add_language.php">Add New</a></h1>
        <table>
            <thead>
                <tr>
                    <th>Language</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($languages as $language): ?>
                    <tr>
                        <td><?php echo $language['language']; ?></td>
                        <td>
                            <a href="edit_language.php?id=<?php echo $language['id']; ?>">Edit</a> |
                            <a href="manage_translations_language.php?id=<?php echo $language['id']; ?>">Manage Translations</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>