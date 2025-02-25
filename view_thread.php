<?php
  require 'db_connect.php';
  
   if(isset($_SESSION['uname'])){
      echo 'Welcome ' . htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8') . ' your access level is ' . htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8') . ' ';
      echo '<a href="logout.php">Logout</a>';
  }
  else{
    echo 'You are not logged in mate ';
    echo '<a href="login.php">Login</a> or <a href="register_form.php">Register</a>';
  }
  
  if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) { 
    // If there is no "id" URL data or it isn't a number
    header("Location: list_threads.php");
    exit;
  }

  // Select details of the specified thread
  $stmt = $db->prepare("SELECT t.thread_id, t.username, t.title, t.content, DATE_FORMAT(t.post_date, '%M %d %Y %r') AS formatted_date, f.forum_name 
                      FROM thread t
                      INNER JOIN forum f on t.forum_id = f.forum_id
                      WHERE t.thread_id = ?");
  $stmt->execute([htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8')]);
  $thread = $stmt->fetch();
  
  if (!$thread) { 
    // If no data (no thread with that ID in the database)
    header("Location: list_threads.php");
    exit;  
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo htmlentities($thread['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="author" content="Greg Baatard" />
    <meta name="description" content="View thread page of forum scenario" />
    <link rel="stylesheet" type="text/css" href="forum_stylesheet.css" />
    <script>
      function validateForm() {
        var form = document.forms["reply_form"];
        var reply = form.reply;

        reply.style.backgroundColor = '';
        if(reply.value.trim()=="") {
          alert("Please enter a reply");
          reply.style.backgroundColor = '#FFC8C8';
          return false;
        }
        return true;
      }
    </script>
  </head>

  <body>
    <h3>View Thread</h3>
    <p><a href="list_threads.php">List</a> | <a href="search_threads.php">Search</a></p>
    <?php
      $safe_title = htmlentities($thread['title'], ENT_QUOTES, 'UTF-8');
      $safe_content = nl2br(htmlentities($thread['content'], ENT_QUOTES, 'UTF-8'));

      echo '<h4>'.$safe_title.'</h4>';
      echo '<p><small><em>Posted by <a href="view_profile.php?username=' . htmlentities($thread['username'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($thread['username'], ENT_QUOTES, 'UTF-8') . '</a></small><br />';
      echo '<small>Posted in ' . htmlentities($thread['forum_name'], ENT_QUOTES, 'UTF-8') . '</small><br />';
      echo '<small>Posted on ' . htmlentities($thread['formatted_date'], ENT_QUOTES, 'UTF-8') . '</small></p>';
      if(isset($_SESSION['uname'])){
      if($thread['username'] == $_SESSION['uname']) {
          echo '<p><small><em><a href="edit_thread_form.php?id=' . htmlentities($thread['thread_id'], ENT_QUOTES, 'UTF-8') . '">Edit</a></em></small></p>';
      }
      if($thread['username'] == $_SESSION['uname'] || $_SESSION['level'] == 1) {
          echo '<p><small><em><a onclick="return confirm(\'Are you sure you want to delete this thread?\')" href="delete_thread.php?id=' . htmlentities($thread['thread_id'], ENT_QUOTES, 'UTF-8') . '">Delete</a></em></small></p>';
      }
    }
      echo '<p>' . $safe_content . '</p>';
    ?>
    <?php
      if(isset($_SESSION['uname'])) {
        echo '<form action="reply.php" method="post" name="reply_form" onsubmit="return validateForm()">';
        echo '<input type="hidden" name="thread_id" value="' . htmlentities($thread['thread_id'], ENT_QUOTES, 'UTF-8') . '">';
        echo '<textarea name="reply" placeholder="Enter your reply"></textarea>';
        echo '<input type="submit" name="submit"></input>';
        echo '</form></br></br>';
      }

      $stmt = $db->prepare("SELECT r.reply_id, r.username, r.content, DATE_FORMAT(r.post_date, '%M %d %Y %r') AS formatted_date
                          FROM reply r
                          INNER JOIN thread t on r.thread_id = t.thread_id
                          WHERE t.thread_id = ?
                          ORDER BY t.post_date DESC");
      $stmt->execute([htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8')]);

      $result_data = $stmt->fetchAll();

      foreach($result_data as $row) {
        echo '<p>' . nl2br(htmlentities($row['content'], ENT_QUOTES, 'UTF-8')) . '</p>';
        echo '<small>Posted by <a href="view_profile.php?username=' . htmlentities($row['username'], ENT_QUOTES, 'UTF-8') . '">' . nl2br(htmlentities($row['username'], ENT_QUOTES, 'UTF-8')) . '</a></small><br />';
        echo '<small>Posted at ' . nl2br(htmlentities($row['formatted_date'], ENT_QUOTES, 'UTF-8')) . '</small></p>';
        echo '</br>';
      }
    ?>
  </body>
</html>
