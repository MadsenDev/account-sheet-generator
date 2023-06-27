<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "cold1234";
$dbname = "acctsheet";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>