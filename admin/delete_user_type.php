<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if (!isset($_GET['id'])) {
    header('Location: manage_user_type.php');
    exit();
}

$user_type_id = $_GET['id'];
$delete_query = "DELETE FROM user_type WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $user_type_id);
mysqli_stmt_execute($stmt);

eventLog($conn, "User type deleted", 'deletion', $user_id);

header('Location: manage_user_type.php');
exit();
?>