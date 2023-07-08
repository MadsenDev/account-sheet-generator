<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to delete category");
    header('Location: login.php');
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if (!isset($_GET['id'])) {
    header('Location: manage_categories.php');
    exit();
}

$category_id = $_GET['id'];
$delete_query = "DELETE FROM categories WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $category_id);
mysqli_stmt_execute($stmt);

eventLog($conn, "Category deleted", $user_id);

header('Location: manage_categories.php');
exit();
?>