<?php
session_start();
require_once '../db.php';
require_once 'functions.php';

checkSession($conn);

$user_id = $_SESSION['user_id']; // Get the user_id from the session

$error = '';

if (!isset($_GET['id'])) {
    header('Location: manage_blocked.php');
    exit();
}

$blocked_ip_id = $_GET['id'];

if (isset($_POST['edit'])) {
    $ip_address = $_POST['ip_address'];
    $block_reason = $_POST['block_reason'];

    $stmt = $conn->prepare("UPDATE blocked_ips SET ip_address = ?, block_reason = ? WHERE id = ?");
    $stmt->bind_param("ssi", $ip_address, $block_reason, $blocked_ip_id);
    
    if ($stmt->execute()) {
        eventLog($conn, "Updated blocked IP $ip_address", 'modification', $user_id);
        header('Location: manage_blocked.php');
        exit();
    } else {
        $error = 'Failed to edit blocked IP.';
    }
}

$result = $conn->query("SELECT * FROM blocked_ips WHERE id = $blocked_ip_id");
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blocked IP</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Edit Blocked IP</h1>
        <a href="manage_blocked.php">Back to Manage Blocked IPs</a>
        
        <form action="edit_blocked.php?id=<?php echo $blocked_ip_id; ?>" method="post">
            <input type="text" name="ip_address" placeholder="IP Address" value="<?php echo $row['ip_address']; ?>" required><br>
            <textarea name="block_reason" placeholder="Block Reason"><?php echo $row['block_reason']; ?></textarea><br>
            <input type="submit" name="edit" value="Edit IP" class="btn">
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>