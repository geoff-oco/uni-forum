<?php
  require 'db_connect.php';
  
   if(isset($_SESSION['uname'])){
	  echo 'Welcome '.htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8').' your access level is '.htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8').' ';
	  echo '<a href="logout.php">Logout</a>';
  }
  else{
	  header('Location: login.php');
  }
  
  if($_SESSION['level']==1){
    if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
    { // If there is no "id" URL data or it isn't a number
      header("Location: list_threads.php");
      exit;
    }
    else{
	    $stmt = $db->prepare("DELETE FROM thread WHERE thread_id = ?");
		$result = $stmt->execute([htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8')]);
		log_event($db, "Delete Thread", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "Thread deleted with id: " . htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'));
    }
  }
  else{
    $errorMessages = array();

    if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
    { // If there is no "id" URL data or it isn't a number
      array_push($errorMessages, "The title can't be blank!\n");
    }
    if (!$_SESSION['uname'])
    { // If there is no "id" URL data or it isn't a number
      array_push($errorMessages, "you cannot delete the thread if you are not the user who penned it!\n");
    }
    if(!empty($errorMessages)){
      // Display error messages and link to previous page
      foreach($errorMessages as $a){
          echo '<p>'. htmlentities($a, ENT_QUOTES, 'UTF-8') . '</p>';
      }
      echo '<p><a href="list_threads.php">List Threads</a></p>';
    }
    else{
      $stmt = $db->prepare("DELETE FROM thread WHERE thread_id = ? AND username = ?");
      $result = $stmt->execute([htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'), htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8')]);
      log_event($db, "Delete Thread", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "Thread deleted with id: " . htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'));

      if($result){
        echo '<p>Thread Deleted!</p>';
        echo '<p><a href="list_threads.php">List Threads</a></p>';
      }
      else{
        echo '<p>Something went wrong</p>';
      }
    }
  }
  
?>
