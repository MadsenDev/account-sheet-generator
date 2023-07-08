<?php
$ip_address = $_SERVER['REMOTE_ADDR'];

// Getting location of IP using ipinfo.io API
$location = '';
$ch = curl_init("http://ipinfo.io/{$ip_address}/json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['city']) && isset($data['country'])) {
        $location = $data['city'] . ', ' . $data['country'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Blocked</title>
    <link rel="stylesheet" href="admin/login.css"> <!-- Using the same CSS as login.php -->
</head>
<body>
    <div class="login-container">
        <img src="logos/favicon.png" alt="Logo" class="logo"> <!-- Logo Image -->
        <div class="blocked-info">
            <h2>You have been blocked from viewing this website</h2>
            <p>Your IP Address: <?php echo $ip_address; ?></p>
            <?php if ($location): ?>
                <p>Location: <?php echo $location; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>