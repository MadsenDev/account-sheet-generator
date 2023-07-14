<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn, 'admin');

$user_id = $_GET['id']; // Get the id of the user to delete

// Delete the user
$query = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);

eventLog($conn, "User deleted", 'deletion', $user_id);

// Redirect back to manage_users.php
header('Location: manage_users.php');
exit();
?>