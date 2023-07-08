<?php
    function eventLog($conn, $info, $user_id = NULL) {
    
        // Get the IP address of the client
        $ip_address = $_SERVER['REMOTE_ADDR'];
    
        // Get the User Agent string of the client
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
        // Prepare the SQL query
        $query = "INSERT INTO event_logs (info, ip_address, user_agent, user_id) VALUES (?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $conn->prepare($query);
        
        // Bind the parameters
        $stmt->bind_param("sssi", $info, $ip_address, $user_agent, $user_id);
        
        // Execute the query
        $stmt->execute();
    }    
?>