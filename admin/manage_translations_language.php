<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$language_id = $_GET['id']; // Get the id of the language to manage

// Fetch the language
$select_query = "SELECT * FROM languages WHERE id = ?";
$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, 'i', $language_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$language = mysqli_fetch_assoc($result);

// Fetch label presets and translations
$label_presets_query = "SELECT lp.id, lp.label AS original_label, t.translation FROM label_presets lp
LEFT JOIN label_preset_translations t ON lp.id = t.label_preset_id AND t.language_id = ?";
$stmt = mysqli_prepare($conn, $label_presets_query);
mysqli_stmt_bind_param($stmt, 'i', $language_id);
mysqli_stmt_execute($stmt);
$label_presets_result = mysqli_stmt_get_result($stmt);
$label_presets = mysqli_fetch_all($label_presets_result, MYSQLI_ASSOC);

// Fetch site labels and translations
$site_labels_query = "SELECT sl.id, sl.label AS original_label, t.translation FROM site_labels sl
LEFT JOIN site_label_translations t ON sl.id = t.site_label_id AND t.language_id = ?";
$stmt = mysqli_prepare($conn, $site_labels_query);
mysqli_stmt_bind_param($stmt, 'i', $language_id);
mysqli_stmt_execute($stmt);
$site_labels_result = mysqli_stmt_get_result($stmt);
$site_labels = mysqli_fetch_all($site_labels_result, MYSQLI_ASSOC);

// Fetch categories and translations
$categories_query = "SELECT c.id, c.name AS original_name, t.translation FROM categories c
LEFT JOIN category_translations t ON c.id = t.category_id AND t.language_id = ?";
$stmt = mysqli_prepare($conn, $categories_query);
mysqli_stmt_bind_param($stmt, 'i', $language_id);
mysqli_stmt_execute($stmt);
$categories_result = mysqli_stmt_get_result($stmt);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

// Fetch brands and translations
$brands_query = "SELECT b.id, b.name AS original_name, t.translation FROM brands b
LEFT JOIN brand_translations t ON b.id = t.brand_id AND t.language_id = ?";
$stmt = mysqli_prepare($conn, $brands_query);
mysqli_stmt_bind_param($stmt, 'i', $language_id);
mysqli_stmt_execute($stmt);
$brands_result = mysqli_stmt_get_result($stmt);
$brands = mysqli_fetch_all($brands_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Translations for <?php echo $language['language']; ?></title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Translations for <?php echo $language['language']; ?></h1>
        <a href="manage_languages.php">Back to Manage Languages</a>
        <form action="bulk_update_translations.php" method="POST">
            <input type="hidden" name="language_id" value="<?php echo $language_id; ?>">
            
            <h2>Label Presets</h2>
            <?php foreach ($label_presets as $label_preset): ?>
                <label for="label_preset_<?php echo $label_preset['id']; ?>"><?php echo $label_preset['original_label']; ?></label>
                <input type="text" id="label_preset_<?php echo $label_preset['id']; ?>" name="label_presets[<?php echo $label_preset['id']; ?>]" value="<?php echo $label_preset['translation']; ?>">
            <?php endforeach; ?>
            
            <h2>Site Labels</h2>
            <?php foreach ($site_labels as $site_label): ?>
                <label for="site_label_<?php echo $site_label['id']; ?>"><?php echo $site_label['original_label']; ?></label>
                <input type="text" id="site_label_<?php echo $site_label['id']; ?>" name="site_labels[<?php echo $site_label['id']; ?>]" value="<?php echo $site_label['translation']; ?>">
            <?php endforeach; ?>
            
            <h2>Categories</h2>
            <?php foreach ($categories as $category): ?>
                <label for="category_<?php echo $category['id']; ?>"><?php echo $category['original_name']; ?></label>
                <input type="text" id="category_<?php echo $category['id']; ?>" name="categories[<?php echo $category['id']; ?>]" value="<?php echo $category['translation']; ?>">
            <?php endforeach; ?>

            <h2>Brands</h2>
            <?php foreach ($brands as $brand): ?>
                <label for="brand_<?php echo $brand['id']; ?>"><?php echo $brand['original_name']; ?></label>
                <input type="text" id="brand_<?php echo $brand['id']; ?>" name="brands[<?php echo $brand['id']; ?>]" value="<?php echo $brand['translation']; ?>">
            <?php endforeach; ?>
            
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
