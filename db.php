<?php
// Database credentials
$servername = "localhost";
$username = "madsensd_madsen";
$password = "data2023";
$dbname = "madsensd_acct";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>