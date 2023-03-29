<html>
<head>
<title>add</title>
</head>
<body>
    <!-- Get all categories -->
<?php
$conn = mysqli_connect("localhost", "madsensd_madsen", "data2023", "madsensd_acct");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sqlCategories = "SELECT id, name FROM categories ORDER BY name ASC";
$stmtCategories = mysqli_prepare($conn, $sqlCategories);
if ($stmtCategories === false) {
    echo mysqli_error($conn);
} else {
    mysqli_stmt_execute($stmtCategories);
    $resultCategories = mysqli_stmt_get_result($stmtCategories);
}
?>

<!-- Add brand form -->
<form action="add_brand.php" method="post" enctype="multipart/form-data">
    <label for="name">Brand Name:</label>
    <input type="text" name="name" id="name" required><br>
        
    <label for="info">Information:</label>
    <textarea name="info" id="info" required></textarea><br>
        
    <label for="category">Category:</label>
    <select name="category_id" id="category">
        <?php
        while ($rowCategories = mysqli_fetch_assoc($resultCategories)) {
            $categoryId = $rowCategories["id"];
            $categoryName = $rowCategories["name"];
            echo "<option value='$categoryId'>$categoryName</option>";
        }
        ?>
    </select><br>
        
    <label for="logo">Logo:</label>
    <input type="file" name="logo" id="logo" required><br>
        
    <label for="active">Active:</label>
    <input type="checkbox" name="active" id="active" value="1"><br>
        
    <input type="submit" value="Add Brand">
</form> 

<?php
mysqli_free_result($resultCategories);
mysqli_close($conn);
?>
     
</body>
</html>