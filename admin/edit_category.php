<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    $update_query = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'si', $name, $id);
    mysqli_stmt_execute($stmt);

    header('Location: manage_categories.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage_categories.php');
    exit();
}

$category_id = $_GET['id'];
$category_query = "SELECT * FROM categories WHERE id = ?";
$stmt = mysqli_prepare($conn, $category_query);
mysqli_stmt_bind_param($stmt, 'i', $category_id);
mysqli_stmt_execute($stmt);
$category = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Category</h1>
        <a href="manage_categories.php">Back to Manage Categories</a>
        <form action="edit_category.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $category['name']; ?>" required>
            <br>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>