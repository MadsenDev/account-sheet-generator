<?php
    function eventLog($conn, $info, $type, $user_id = NULL) {
    
        // Get the IP address of the client
        $ip_address = $_SERVER['REMOTE_ADDR'];
    
        // Get the User Agent string of the client
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
        // Prepare the SQL query
        $query = "INSERT INTO event_logs (info, type, ip_address, user_agent, user_id) VALUES (?, ?, ?, ?, ?)";
        
        // Prepare statement
        $stmt = $conn->prepare($query);
        
        // Bind the parameters
        $stmt->bind_param("ssssi", $info, $type, $ip_address, $user_agent, $user_id);
        
        // Execute the query
        $stmt->execute();
    }    

    function checkSession($conn, $requiredRole = null) {
        if (!isset($_SESSION['user_id'])) {
            $filename = basename($_SERVER['PHP_SELF']); // get the filename of the current script
            $logMessage = "Unauthorized access attempt to: " . $filename;
            eventLog($conn, $logMessage, 'access');
            header('Location: login.php');
            exit(); // ensure the rest of the script doesn't execute after redirection
        }
        
        if ($requiredRole) {
            // Fetch the role of the logged in user
            $query = "SELECT role FROM users WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            
            if (!$user || $user['role'] !== $requiredRole) {
                $filename = basename($_SERVER['PHP_SELF']); // get the filename of the current script
                $logMessage = "Unauthorized role attempt to: " . $filename;
                eventLog($conn, $logMessage, 'access');
                header('Location: login.php');
                exit(); // ensure the rest of the script doesn't execute after redirection
            }
        }
    }    
?>