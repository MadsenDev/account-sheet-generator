<?php
session_start();
require_once '../db.php';
require_once 'functions.php';
checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Categories <a href="add_category.php">Add New</a></h1>
        <a href="dashboard.php">Back to Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($categories_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <a href="edit_category.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_category.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>