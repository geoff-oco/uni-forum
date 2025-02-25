<?php
  require 'db_connect.php';
  
  $logged_in = false;
  
  if(isset($_SESSION['uname'])){
      $logged_in = true;
      echo 'Welcome ' . htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8') . ' your access level is ' . htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8') . ' ';
      echo '<a href="logout.php">Logout</a>';
  } else {
      echo 'You are not logged in mate ';
      echo '<a href="login.php">Login</a> or <a href="register_form.php">Register</a>';
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>List Threads</title>
    <meta name="author" content="Greg Baatard" />
    <meta name="description" content="List threads page of forum scenario" />
    <link rel="stylesheet" type="text/css" href="forum_stylesheet.css" />
  </head>

  <body>
    <h3>List Threads</h3>
    <p><a href="search_threads.php">Search</a>
    <?php
        if($logged_in){
            echo ' | <a href="new_thread_form.php">New Thread</a>';
        }
        if($logged_in && $_SESSION['level'] == 1){
            echo ' | <a href="change_access_level.php">Change Access Level</a>';
            echo ' | <a href="view_logs.php">View Logs</a>';
        }
        echo '</p>';
    ?>
    <form name="list_threads" method="get" action="list_threads.php" >
      <p><input type="button" value="Show All Threads" onclick="window.location.href = 'list_threads.php'" /> or filter to
        <select name="forum_id">
          <option value="" selected disabled>Select a forum</option>
          <?php  
            // Select details of all forums
            $result = $db->query("SELECT * FROM forum ORDER BY forum_id");
      
            // Loop through each forum to generate an option of the drop-down list
            foreach($result as $row)
            {
                echo '<option value="' . htmlentities($row['forum_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['forum_name'], ENT_QUOTES, 'UTF-8') . '</option>';
        
                // If there is a forum_id in the URL data, assign the current forum's name to a variable to display later
                if (isset($_GET['forum_id']) && $_GET['forum_id'] == $row['forum_id'])
                {
                    $current_forum_name = htmlentities($row['forum_name'], ENT_QUOTES, 'UTF-8');
                }
            }
          ?>
        </select> <input type="submit" value="Filter" />
      </p>
    </form>
    
    <?php
      // Execute a query with or without a WHERE clause depending on whether there's a forum_id in the URL data
      if (isset($_GET['forum_id']))
      {
        echo '<h4>' . $current_forum_name . ' Threads</h4>';
        
        $stmt = $db->prepare("SELECT t.thread_id, t.username, t.title, DATE_FORMAT(t.post_date, '%M %d %Y %r') AS formatted_date, f.forum_name 
                      FROM thread t
                      INNER JOIN forum f on t.forum_id = f.forum_id
                      WHERE t.forum_id = ? 
                      ORDER BY t.post_date DESC");

        $stmt->execute([htmlentities($_GET['forum_id'], ENT_QUOTES, 'UTF-8')]);
      }
      else
      {
        echo '<h4>All Threads</h4>';
        
        $stmt = $db->prepare("SELECT t.thread_id, t.username, t.title, DATE_FORMAT(t.post_date, '%M %d %Y %r') AS formatted_date, f.forum_name 
                      FROM thread t
                      INNER JOIN forum f on t.forum_id = f.forum_id
                      ORDER BY t.post_date DESC");
                              
        $stmt->execute();
      }
      
      // Fetch all of the results as an array
      $result_data = $stmt->fetchAll();
      
      // Display results or a "no threads" message as appropriate
      if (count($result_data) > 0)
      {      
        // Loop through results to display links to threads
        foreach($result_data as $row)
        {
            echo '<p><a href="view_thread.php?id=' . htmlentities($row['thread_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['title'], ENT_QUOTES, 'UTF-8') . '</a><br />';
            echo '<small>Posted by <a href="view_profile.php?username=' . htmlentities($row['username'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['username'], ENT_QUOTES, 'UTF-8') . '</a></small><br />';
            echo '<small>Posted in ' . htmlentities($row['forum_name'], ENT_QUOTES, 'UTF-8') . '</small><br />';
            echo '<small>Posted at ' . htmlentities($row['formatted_date'], ENT_QUOTES, 'UTF-8') . '</small></p>';
        }
        $threadcount = count($result_data);
        if ($threadcount == 1){
            echo '<p>There is ' . htmlentities($threadcount, ENT_QUOTES, 'UTF-8') . ' listed thread.</p>';
        }
        else{
            echo '<p>There are ' . htmlentities($threadcount, ENT_QUOTES, 'UTF-8') . ' listed threads.</p>';
        }
      }
      else
      {
        echo '<p>No threads posted.</p>';
      }
    ?>
  </body>
</html>
