<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once '../db.php';

$user_types_query = "SELECT * FROM user_type";
$user_types_result = mysqli_query($conn, $user_types_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Types</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage User Types <a href="add_user_type.php">Add New</a></h1>
        <a href="dashboard.php">Back to Dashboard</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Logo Left</th>
                    <th>Logo Right</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($user_types_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['logo_left']; ?></td>
                        <td><?php echo $row['logo_right']; ?></td>
                        <td>
                            <a href="edit_user_type.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_user_type.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user type?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>