<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$logs_query = "SELECT event_logs.id, event_logs.info, event_logs.ip_address, event_logs.user_agent, users.username, event_logs.timestamp FROM event_logs LEFT JOIN users ON event_logs.user_id = users.id ORDER BY event_logs.timestamp DESC";

$logs_result = mysqli_query($conn, $logs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Logs</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Event Logs</h1>
        <a href="dashboard.php">Back to Dashboard</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Info</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Username</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($logs_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['info']; ?></td>
                        <td><?php echo $row['ip_address']; ?></td>
                        <td><?php echo $row['user_agent']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['timestamp']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>