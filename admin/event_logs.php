<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$type = '';
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $logs_query = "SELECT event_logs.id, event_logs.info, event_logs.type, event_logs.ip_address, event_logs.user_agent, users.username, event_logs.timestamp FROM event_logs LEFT JOIN users ON event_logs.user_id = users.id WHERE event_logs.type = '{$type}' ORDER BY event_logs.timestamp DESC";
} else {
    $logs_query = "SELECT event_logs.id, event_logs.info, event_logs.type, event_logs.ip_address, event_logs.user_agent, users.username, event_logs.timestamp FROM event_logs LEFT JOIN users ON event_logs.user_id = users.id ORDER BY event_logs.timestamp DESC";
}

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

        <?php
            // Calculate the counts
            $typeCounts = [];
            $types = ['access', 'addition', 'modification', 'deletion', 'print'];

            foreach ($types as $t) {
                $count_query = "SELECT COUNT(*) AS count FROM event_logs WHERE type = ?";
                $stmt = $conn->prepare($count_query);
                $stmt->bind_param("s", $t);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $typeCounts[$t] = $row['count'];
            }

            // Display the dropdown
            echo '<form action="event_logs.php" method="get" id="type-form">';
            echo '<div class="form-group">';
            echo '<label for="type">Filter by type:</label>';
            echo '<select id="type" name="type" onchange="document.getElementById(\'type-form\').submit();">';
            echo '<option value="">Show All</option>';

            foreach ($types as $t) {
                $selected = ($type === $t) ? 'selected' : '';
                echo "<option value=\"$t\" $selected>$t ({$typeCounts[$t]})</option>";
            }

            echo '</select>';
            echo '</div>';
            echo '</form>';
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Info</th>
                    <th>Type</th>
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
                        <td><?php echo $row['type']; ?></td>
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