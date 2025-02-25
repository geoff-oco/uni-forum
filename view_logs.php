<?php
	require 'db_connect.php';
	
	if(isset($_SESSION['uname']) && $_SESSION['level'] == 1){
		
        echo '<h1>Event Logs</h1>';
        echo '<p><a href="list_threads.php">List Threads</a> | <a href="search_threads.php">Search Threads</a></p>';
        echo '<p><a href="logout.php">Logout</a></p>';
        echo '<table>';
        echo '<tr><th>log ID</th><th>Date</th><th>Event Type</th><th>Username</th><th>IP Address</th><th>Event Details</th></tr>';
        $stmt = $db->prepare("SELECT * FROM event_log");
        $stmt->execute();
        $logs = $stmt->fetchAll();
        foreach($logs as $log){
            echo '<tr><td>'.$log['log_id'].'</td><td>'.$log['log_date'].'</td><td>'.$log['event_type'].'</td><td>'.$log['username'].'</td><td>'.$log['ip_address'].'</td><td>'.$log['event_details'].'</td></tr>';
        }
        echo '</table>';
	}
	else{
		header('Location: list_threads.php');
	}
?>