<html>
<head>
<title>add</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  form {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="text"],
  input[type="file"],
  input[type="email"],
  input[type="password"],
  textarea,
  select {
    width: 100%;
    padding: 8px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
  }

  input[type="checkbox"] {
    margin-top: 10px;
  }

  input[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    margin-top: 10px;
  }

  input[type="submit"]:hover {
    background-color: #2980b9;
  }
</style>
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