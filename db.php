<?php
// Database credentials
$servername = "localhost";
$username = "madsyrnh_chris";
$password = "data2023";
$dbname = "madsyrnh_account";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>