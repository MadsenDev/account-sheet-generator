<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$translations_query = "SELECT t.id, lp.label AS original_label, l.language, t.translation FROM translations t 
JOIN label_presets lp ON t.label_preset_id = lp.id 
JOIN languages l ON t.language_id = l.id";

$translations_result = mysqli_query($conn, $translations_query);
$translations = mysqli_fetch_all($translations_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Translations</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Translations <a href="add_translation.php">Add New</a></h1>
        <a href="add_translation.php">Add New Translation</a>
        <table>
            <thead>
                <tr>
                    <th>Original Label (English)</th>
                    <th>Language</th>
                    <th>Translation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($translations as $translation): ?>
                    <tr>
                        <td><?php echo $translation['original_label']; ?></td>
                        <td><?php echo $translation['language']; ?></td>
                        <td><?php echo $translation['translation']; ?></td>
                        <td>
                            <a href="edit_translation.php?id=<?php echo $translation['id']; ?>">Edit</a> |
                            <a href="delete_translation.php?id=<?php echo $translation['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>