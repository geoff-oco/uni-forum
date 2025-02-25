<?php
	require 'db_connect.php';
	session_start();
	session_destroy();
	log_event($db, "Logout", $_SESSION['uname'], "Logout successful");
	header('Location: list_threads.php');
?>