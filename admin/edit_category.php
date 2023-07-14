<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

    $update_query = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'si', $name, $id);
    mysqli_stmt_execute($stmt);
    eventLog($conn, "Category updated", 'modification', $user_id);

    header('Location: manage_categories.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage_categories.php');
    exit();
}

$category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
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
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            <br>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>