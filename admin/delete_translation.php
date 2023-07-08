<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to delete translation");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

// Fetch the translation to delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_query = "DELETE FROM translations WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    eventLog($conn, "Translation deleted", $user_id);

    header('Location: manage_translations.php');
    exit();
}

// Display an error message if no id is provided
echo "Error: No id provided";
exit();
?>