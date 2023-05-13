<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

if (!isset($_GET['id'])) {
    header('Location: manage_user_type.php');
    exit();
}

$user_type_id = $_GET['id'];
$delete_query = "DELETE FROM user_type WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
mysqli_stmt_execute($stmt);

header('Location: manage_user_type.php');
exit();
?>