<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$language_id = $_POST['language_id']; // Get the id of the language to update

$label_presets = $_POST['label_presets']; // Get the label preset translations
$site_labels = $_POST['site_labels']; // Get the site label translations
$categories = $_POST['categories']; // Get the category translations
$brands = $_POST['brands']; // Get the brand translations

// Iterate through the label presets
foreach ($label_presets as $label_preset_id => $translation) {
    $update_query = "INSERT INTO label_preset_translations (label_preset_id, language_id, translation)
    VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'iiss', $label_preset_id, $language_id, $translation, $translation);
    mysqli_stmt_execute($stmt);
}

// Iterate through the site labels
foreach ($site_labels as $site_label_id => $translation) {
    $update_query = "INSERT INTO site_label_translations (site_label_id, language_id, translation)
    VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'iiss', $site_label_id, $language_id, $translation, $translation);
    mysqli_stmt_execute($stmt);
}

// Iterate through the categories
foreach ($categories as $category_id => $translation) {
    $update_query = "INSERT INTO category_translations (category_id, language_id, translation)
    VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'iiss', $category_id, $language_id, $translation, $translation);
    mysqli_stmt_execute($stmt);
}

// Iterate through the brands
foreach ($brands as $brand_id => $translation) {
    $update_query = "INSERT INTO brand_translations (brand_id, language_id, translation)
    VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'iiss', $brand_id, $language_id, $translation, $translation);
    mysqli_stmt_execute($stmt);
}

eventLog($conn, "Bulk update translations", 'modification', $user_id);

header('Location: manage_translations_language.php?id=' . $language_id);
exit();
?>