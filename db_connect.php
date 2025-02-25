<?php
	session_start();


  // Connect to database server
  try
  { 
    $db = new PDO('mysql:host=localhost;port=6033;dbname=iwd_forum', 'root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
  catch (PDOException $e) 
  {
    echo 'Error connecting to database server:<br />';
    echo $e->getMessage();
    exit;
  } 

  function log_event($db, $event_type, $username, $event_details){
    $logstmt = $db->prepare("INSERT INTO event_log (event_type, username, ip_address, event_details) VALUES (?, ?, ?, ?)");
    $result = $logstmt->execute([$event_type, $username, $_SERVER['REMOTE_ADDR'], $event_details]);
      
    if(!$result){
      echo $logstmt->errorCode();
    }

  }
?>