<?php
// Establish a database connection
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

// Get the form data
$name = $_POST['name'];
$info = $_POST['info'];
$category_id = $_POST['category_id'];
$active = isset($_POST['active']) ? 1 : 0;

// Handle the file upload
if (isset($_FILES['logo'])) {
    $file_name = $_FILES['logo']['name'];
    $file_tmp = $_FILES['logo']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $file_path = "uploads/" . uniqid() . "." . $file_ext;

    if (move_uploaded_file($file_tmp, $file_path)) {
        // Prepare the INSERT statement
        $stmt = mysqli_prepare($conn, "INSERT INTO `brands` (`logo_path`, `name`, `info`, `category_id`, `active`) VALUES (?, ?, ?, ?, ?)");

        // Bind the parameters to the statement
        mysqli_stmt_bind_param($stmt, "ssisi", $file_path, $name, $info, $category_id, $active);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Brand added successfully <br>";
            echo "<a href='add.php'>Add another one</a>";
        } else {
            echo "Error adding brand: " . mysqli_error($conn);
        }

        // Close the statement and the connection
        mysqli_stmt_close($stmt);
    } else {
        echo "Error uploading logo: " . $_FILES['logo']['error'];
    }
} else {
    echo "No logo uploaded";
}

mysqli_close($conn);
?>