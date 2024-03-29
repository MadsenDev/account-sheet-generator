<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if (!isset($_GET['id'])) {
    header('Location: manage_blocked.php');
    exit();
}

$blocked_ip_id = $_GET['id'];
$delete_query = "DELETE FROM blocked_ips WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, 'i', $blocked_ip_id);
mysqli_stmt_execute($stmt);

eventLog($conn, "Deleted IP from blocked", 'deletion', $user_id);

header('Location: manage_blocked.php');
exit();
?>