<?php
// Establish a database connection
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

// Get the form data
$name = $_POST['name'];
$info = $_POST['info'];
$category = $_POST['category'];
$active = isset($_POST['active']) ? 1 : 0;
$logo = file_get_contents($_FILES['logo']['tmp_name']);

// Prepare the INSERT statement
$stmt = mysqli_prepare($conn, "INSERT INTO `brands` (`logo`, `name`, `info`, `category`, `active`) VALUES (?, ?, ?, ?, ?)");

// Bind the parameters to the statement
mysqli_stmt_bind_param($stmt, "bsbsi", $logo, $name, $info, $category, $active);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    echo "Brand added successfully";
} else {
    echo "Error adding brand: " . mysqli_error($conn);
}

// Close the statement and the connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>