<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to edit translation");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

// Fetch the translation to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $select_query = "SELECT * FROM translations WHERE id = ?";
    $stmt = $conn->prepare($select_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $translation = $result->fetch_assoc();

    // Fetch label presets
    $label_presets_query = "SELECT * FROM label_presets";
    $label_presets_result = mysqli_query($conn, $label_presets_query);
    $label_presets = mysqli_fetch_all($label_presets_result, MYSQLI_ASSOC);

    // Fetch languages
    $languages_query = "SELECT * FROM languages";
    $languages_result = mysqli_query($conn, $languages_query);
    $languages = mysqli_fetch_all($languages_result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $label_preset_id = $_POST['label_preset_id'];
    $language_id = $_POST['language_id'];
    $translation_text = $_POST['translation'];

    $update_query = "UPDATE translations SET label_preset_id = ?, language_id = ?, translation = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    if (!$stmt) {
        // Handle the error here, e.g. log it and display an error message to the user
        $error_message = "Failed to prepare SQL statement: " . mysqli_error($conn);
        eventLog($conn, $error_message);
        die($error_message);
    }
    mysqli_stmt_bind_param($stmt, 'iisi', $label_preset_id, $language_id, $translation_text, $id);
    mysqli_stmt_execute($stmt);

    eventLog($conn, "Translation updated", $user_id);

    header('Location: manage_translations.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Translation</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Translation</h1>
        <a href="manage_translations.php">Back to Manage Translations</a>
        <form action="edit_translation.php?id=<?php echo $id; ?>" method="POST">
            <label for="label_preset_id">Original Label (English):</label>
            <select id="label_preset_id" name="label_preset_id" required>
                <?php foreach ($label_presets as $label_preset): ?>
                    <option value="<?php echo $label_preset['id']; ?>" <?php if ($label_preset['id'] == $translation['label_preset_id']) echo 'selected'; ?>><?php echo $label_preset['label']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="language_id">Language to Translate To:</label>
            <select id="language_id" name="language_id" required>
                <?php foreach ($languages as $language): ?>
                    <option value="<?php echo $language['id']; ?>" <?php if ($language['id'] == $translation['language_id']) echo 'selected'; ?>><?php echo $language['language']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="translation">Translation:</label>
            <input type="text" id="translation" name="translation" required value="<?php echo $translation['translation']; ?>">
            <br>
            <button type="submit">Update Translation</button>
        </form>
    </div>
</body>
</html>