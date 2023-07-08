<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    eventLog($conn, "Unauthorized access attempt to delete brand");
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if (!isset($_GET['id'])) {
    header('Location: manage_brands.php');
    exit();
}

$brand_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Get the user_id from the session
$delete_query = "DELETE FROM brands WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $brand_id);
mysqli_stmt_execute($stmt);

eventLog($conn, "Brand deleted", $user_id);

header('Location: manage_brands.php');
exit();
?>