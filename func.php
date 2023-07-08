<?php
    function isIpBlocked($conn, $ip_address) {
        $query = "SELECT * FROM blocked_ips WHERE ip_address = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->num_rows > 0;
    }

    // Define a function to check if this is a unique daily visit
function isUniqueDailyVisit() {
    // Check if the cookie is already set
    if (isset($_COOKIE['unique_daily_visitor'])) {
        // Cookie is set, not a unique visit
        return false;
    } else {
        // Cookie is not set, set the cookie and mark as a unique visit
        setcookie('unique_daily_visitor', uniqid(), time() + 86400, "/"); // Cookie expires in 24 hours
        return true;
    }
}
?>