<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$search = isset($_GET['search']) ? $_GET['search'] : '';

$blocked_query = "SELECT * FROM blocked_ips";

if ($search !== '') {
    $blocked_query .= " WHERE ip_address LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}

$blocked_result = mysqli_query($conn, $blocked_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blocked IPs</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Blocked IPs <a href="add_blocked.php">Add New</a></h1>
        <a href="dashboard.php">Back to Dashboard</a>
        
        <form action="manage_blocked.php" method="get">
            <input type="text" name="search" placeholder="Search IPs" value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP Address</th>
                    <th>Blocked On</th>
                    <th>Block Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($blocked_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['ip_address']; ?></td>
                        <td><?php echo $row['blocked_on']; ?></td>
                        <td><?php echo $row['block_reason']; ?></td>
                        <td>
                            <a href="edit_blocked.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_blocked.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to unblock this IP?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>