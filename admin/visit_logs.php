<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    eventLog("Unauthorized access attempt to manage visit logs");
    header('Location: login.php');
}

$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

$visits_query = "SELECT * FROM unique_daily_visits";

if ($date_filter !== '') {
    $visits_query .= " WHERE visit_date = '" . mysqli_real_escape_string($conn, $date_filter) . "'";
}

$visits_result = mysqli_query($conn, $visits_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Logs</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Visit Logs</h1>
        <a href="dashboard.php">Back to Dashboard</a>
        
        <form action="visit_logs.php" method="get">
            <input type="date" name="date_filter" value="<?php echo $date_filter; ?>">
            <button type="submit">Filter</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Visit Date</th>
                    <th>IP Address</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($visits_result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['visit_date']; ?></td>
                        <td><?php echo $row['ip_address']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>