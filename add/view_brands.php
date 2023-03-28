<?php
// Establish a database connection
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

// Prepare the SELECT statement
$stmt = mysqli_prepare($conn, "SELECT `id`, `logo`, `name`, `info` FROM `brands` WHERE `active` = 1");

// Execute the statement
mysqli_stmt_execute($stmt);

// Bind the result variables
mysqli_stmt_bind_result($stmt, $id, $logo, $name, $info);

// Loop through the results and display them
while (mysqli_stmt_fetch($stmt)) {
    echo "<img src='data:image/png;base64," . base64_encode($logo) . "' alt='" . $name . " logo'><br>";
    echo "<h2>" . $name . "</h2>";
    echo "<p>" . $info . "</p>";
}

// Close the statement and the connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>