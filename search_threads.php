<?php
  require 'db_connect.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Search Threads</title>
    <meta name="author" content="Greg Baatard" />
    <meta name="description" content="Search threads page of forum scenario" />
    <link rel="stylesheet" type="text/css" href="forum_stylesheet.css" />
  </head>

  <body>
    <h3>Search Threads</h3>
    <p><a href="list_threads.php">List</a> | <a href="new_thread_form.php">New Thread</a></p>
    <form name="search_threads" method="get" action="search_threads.php" >
      <p>Search: <input type="text" name="search_term" placeholder="Enter search term..." autofocus /> <input type="submit" value="Submit" /></p>
    </form>
    
    <?php
      // Execute a query if there's a search term in the URL data
      if (isset($_GET['search_term']))
      {
        echo '<h4>Search results for "' . htmlentities($_GET['search_term'], ENT_QUOTES, 'UTF-8') . '"</h4>';
        
        // Put wildcard characters on each end of the search term
        $search_term = '%' . htmlentities($_GET['search_term'], ENT_QUOTES, 'UTF-8') . '%';
        
        $stmt = $db->prepare("SELECT t.thread_id, t.username, t.title, DATE_FORMAT(t.post_date, '%M %d %Y %r') AS formatted_date, f.forum_name, f.forum_id 
                              FROM thread t
							  INNER JOIN forum f on t.forum_id = f.forum_id
                              WHERE t.title LIKE ? OR t.content LIKE ? ORDER BY t.post_date DESC");

        // Provide the same value for both placeholders to search the title and content columns
        $stmt->execute([$search_term, $search_term]);
        
        
        // Fetch all of the results as an array
        $result_data = $stmt->fetchAll();
        
        // Display results or a "no results" message as appropriate
        if (count($result_data) > 0)
        {          
          // Loop through results to display links to threads
          foreach($result_data as $row)
          {
              echo '<p><a href="view_thread.php?id=' . htmlentities($row['thread_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['title'], ENT_QUOTES, 'UTF-8') . '</a><br />';
              echo '<small>Posted by <a href="view_profile.php?username=' . htmlentities($row['username'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['username'], ENT_QUOTES, 'UTF-8') . '</a></small><br />';
              echo '<small>Posted in <a href="list_threads.php?forum_id=' . htmlentities($row['forum_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlentities($row['forum_name'], ENT_QUOTES, 'UTF-8') . '</a></small><br />';
              echo '<small>Posted at ' . htmlentities($row['formatted_date'], ENT_QUOTES, 'UTF-8') . '</small></p>';
          }
          
          $threadcount = count($result_data);
          if ($threadcount == 1){
              echo '<p>There is ' . htmlentities($threadcount, ENT_QUOTES, 'UTF-8') . ' listed thread.</p>';
          } else {
              echo '<p>There are ' . htmlentities($threadcount, ENT_QUOTES, 'UTF-8') . ' listed threads.</p>';
          }
        }
        else
        {
          echo '<p>No results found.</p>';
        }
      }
    ?>
  </body>
</html>
