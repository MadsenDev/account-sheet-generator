<?php
// Establish a database connection
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

// Get the search query from the AJAX request
$query = $_GET['query'];

// Prepare the SELECT statement
$stmt = mysqli_prepare($conn, "SELECT `id`, `name` FROM `brands` WHERE `name` LIKE ?");

// Bind the search query to the statement
$search = "%" . $query . "%";
mysqli_stmt_bind_param($stmt, "s", $search);

// Execute the statement
mysqli_stmt_execute($stmt);

// Bind the result variables
mysqli_stmt_bind_result($stmt, $id, $name);

// Fetch the results and store them in an array
$results = array();
while (mysqli_stmt_fetch($stmt)) {
  $results[] = array("id" => $id, "name" => $name);
}

// Close the statement and the connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Return the results in JSON format
echo json_encode($results);
?>
