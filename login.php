<?php
	require 'db_connect.php';
	
	if(isset($_SESSION['uname'])){
		header('Location: list_threads.php');
	}

	if(isset($_POST['submit'])){
		$stmt = $db->prepare("SELECT * FROM user WHERE username=?");
		$stmt->execute([$_POST['uname']]);
		$user = $stmt->fetch();
		
		if($user && password_verify($_POST['pword'], $user['password'])){
			log_event($db, "Login", $user['username'], "Login successful");
			$_SESSION['uname'] = $user['username'];
			$_SESSION['level'] = $user['access_level'];
			header('Location: list_threads.php');
			exit;
		}
		else{
			log_event($db, "Login", $user['username'], "Login failed");
			echo 'invalid credentials, try again.';
		}
	}



?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<meta name="author" content="Geoffrey O'Connell" />
		<meta name="description" content="A form to login" />
	</head>
	<body>
		<h1>Login</h1>
	<p><a href="list_threads.php">List Threads</a> | <a href="search_threads.php">Search Threads</a></p>
		<form name="login_form" method="post" action="login.php">
			<p><input type="text" name="uname" placeholder="Username" title="Username" /></p>
			<p><input type="password" name="pword" placeholder="Password" title="Password" /></p>
			<p><input type="submit" name="submit"></p>
		</form>
	</body>

</html>