<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../db.php';

if (!isset($_GET['id'])) {
    header('Location: manage_brands.php');
    exit();
}

$brand_id = $_GET['id'];
$delete_query = "DELETE FROM brands WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $brand_id);
mysqli_stmt_execute($stmt);

header('Location: manage_brands.php');
exit();
?>