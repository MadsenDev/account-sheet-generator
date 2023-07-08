<?php
session_start();

require_once '../db.php';
require_once 'functions.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the user_id from the session

    eventLog($conn, "Used logged out");

    // Unset session variable
    unset($_SESSION['user_id']);
}

header('Location: login.php');
?>