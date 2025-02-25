<?php
  require 'db_connect.php';

  if (!isset($_GET['username']) || empty(trim($_GET['username']))) { 
    header("Location: list_threads.php");
    exit;
  } else {
    // Prepare the statement
    $stmt = $db->prepare("SELECT u.username, u.real_name, YEAR(u.dob) AS dob_year, COUNT(t.thread_id) AS thread_count
                        FROM user u
                        LEFT JOIN thread t on u.username = t.username
                        WHERE u.username = ?");
    $stmt->execute([$_GET['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) { 
      header("Location: list_threads.php");
      exit;  
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlentities($user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="author" content="Greg Baatard" />
    <meta name="description" content="View user profile of forum scenario" />
    <link rel="stylesheet" type="text/css" href="forum_stylesheet.css" />
</head>
<body>
<h3>View User Profile</h3>
<p><a href="javascript:history.back()">Go back to previous</a></p>
<p><a href="list_threads.php">List Threads</a> | <a href="search_threads.php">Search Threads</a></p>
<?php
if (empty($user['real_name'])) {
    $actual_name = "<em>not disclosed</em>";
} else {
    $actual_name = htmlentities($user['real_name'], ENT_QUOTES, 'UTF-8');
}

// Safeguarding each output
echo '<h4>' . htmlentities($user['username'] ?? '', ENT_QUOTES, 'UTF-8') . '</h4>';
echo '<p>Real Name: ' . $actual_name . '<br />';
echo '<p>Born in: ' . htmlentities($user['dob_year'] ?? 'Unknown DOB', ENT_QUOTES, 'UTF-8') . '<br />';
echo '<p>Post Count: ' . htmlentities($user['thread_count'] ?? '0', ENT_QUOTES, 'UTF-8') . '<br /></p>';
?>
</body>
</html>
