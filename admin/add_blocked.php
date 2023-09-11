<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$error = '';

if (isset($_POST['add'])) {
    $ip_address = $_POST['ip_address'];
    $block_reason = $_POST['block_reason'];

    $stmt = $conn->prepare("INSERT INTO blocked_ips (ip_address, block_reason) VALUES (?, ?)");
    $stmt->bind_param("ss", $ip_address, $block_reason);
    
    if ($stmt->execute()) {
        eventLog($conn, "Added IP $ip_address to blocked", 'addition', $user_id);
        header('Location: manage_blocked.php');
        exit();
    } else {
        $error = 'Failed to add blocked IP.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blocked IP</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Add Blocked IP</h1>
        <a href="manage_blocked.php">Back to Manage Blocked IPs</a>
        
        <form action="add_blocked.php" method="post">
            <input type="text" name="ip_address" placeholder="IP Address" required><br>
            <textarea name="block_reason" placeholder="Block Reason"></textarea><br>
            <input type="submit" name="add" value="Add IP" class="btn">
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>