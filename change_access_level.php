<?php
  require 'db_connect.php';
  
   if(isset($_SESSION['uname'])){
	   if($_SESSION['level']!=1){
		   header('Location: list_threads.php');
	   }
	   else{
		   	echo 'Welcome '.htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8').' your access level is '.htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8').' ';
			echo '<a href="logout.php">Logout</a>';
	   }
  }
  else{
	  header('Location: login.php');
  }
  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Change Access Level</title>
    <meta name="author" content="Greg Baatard" />
    <meta name="description" content="Change access level of users" />
    <link rel="stylesheet" type="text/css" href="forum_stylesheet.css" />
  </head>

  <body>
    <h3>Change Access Level</h3>
    <p><a href="javascript:history.back()">Back</a>
    <p><a href="list_threads.php">List Threads</a> | <a href="search_threads.php">Search Threads</a></p>
    <form name="list_users" method="post" action="change_access.php" >
        <select name="username">
          <option value="" selected disabled>Select a user</option>
          <?php  
            // Select details of all forums
            $result = $db->query("SELECT * FROM user");
      
            // Loop through each forum to generate an option of the drop-down list
            foreach($result as $row)
            {
              echo '<option value="'.htmlentities($row['username'], ENT_QUOTES, 'UTF-8').'">'.htmlentities($row['username'], ENT_QUOTES, 'UTF-8').'</option>';
            }
          ?>
        </select> <input type="submit" value="Promote/Demote" />
      </p>
    </form>
    
	
  </body>
  </html>
