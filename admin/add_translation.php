<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to add translation");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $label_preset_id = $_POST['label_preset_id'];
    $language_id = $_POST['language_id'];
    $translation = $_POST['translation'];

    $insert_query = "INSERT INTO translations (label_preset_id, language_id, translation) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    if (!$stmt) {
        // Handle the error here, e.g. log it and display an error message to the user
        $error_message = "Failed to prepare SQL statement: " . mysqli_error($conn);
        eventLog($conn, $error_message);
        die($error_message);
    }
    mysqli_stmt_bind_param($stmt, 'iis', $label_preset_id, $language_id, $translation);
    mysqli_stmt_execute($stmt);

    eventLog($conn, "Translation added", $user_id);

    header('Location: manage_translations.php');
    exit();
}

// Fetch label presets
$label_presets_query = "SELECT * FROM label_presets";
$label_presets_result = mysqli_query($conn, $label_presets_query);
$label_presets = mysqli_fetch_all($label_presets_result, MYSQLI_ASSOC);

// Fetch languages
$languages_query = "SELECT * FROM languages";
$languages_result = mysqli_query($conn, $languages_query);
$languages = mysqli_fetch_all($languages_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Translation</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add Translation</h1>
        <a href="manage_translations.php">Back to Manage Translations</a>
        <form action="add_translation.php" method="POST">
            <label for="label_preset_id">Original Label (English):</label>
            <select id="label_preset_id" name="label_preset_id" required>
                <?php foreach ($label_presets as $label_preset): ?>
                    <option value="<?php echo $label_preset['id']; ?>"><?php echo $label_preset['label']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="language_id">Language to Translate To:</label>
            <select id="language_id" name="language_id" required>
                <?php foreach ($languages as $language): ?>
                    <option value="<?php echo $language['id']; ?>"><?php echo $language['language']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="translation">Translation:</label>
            <input type="text" id="translation" name="translation" required>
            <br>
            <button type="submit">Add Translation</button>
        </form>
    </div>
</body>
</html>