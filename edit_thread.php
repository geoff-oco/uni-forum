<?php
require 'db_connect.php';

if(isset($_SESSION['uname'])){
    echo 'Welcome ' . htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8') . ' your access level is ' . htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8') . ' ';
    echo '<a href="logout.php">Logout</a>';
} else {
    header('Location: login.php');
}

if (isset($_POST['submit'])) {
    $errorMessages = array();
    $title = $_POST['title'];
    $content = $_POST['content'];
    $forum = $_POST['forum'];

    // Check for validation errors
    if(trim($title) == ''){
        array_push($errorMessages, "The title can't be blank!\n");
    }
    if(trim($content) == ''){
        array_push($errorMessages, "The content can't be blank!\n");
    }
    if(trim($forum) == ''){
        array_push($errorMessages, "A forum must be chosen!\n");
    }

    if(!empty($errorMessages)){
        // Display error messages and link to previous page
        foreach($errorMessages as $a){
            echo '<p>' . htmlentities($a, ENT_QUOTES, 'UTF-8') . '</p>';
        }
    } else {
        $stmt = $db->prepare("UPDATE thread SET title = ?, content = ?, forum_id = ? WHERE thread_id = ? AND username = ?");
        $result = $stmt->execute([
            htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'),
            htmlentities($_POST['content'], ENT_QUOTES, 'UTF-8'),
            htmlentities($_POST['forum'], ENT_QUOTES, 'UTF-8'),
            htmlentities($_POST['thread_id'], ENT_QUOTES, 'UTF-8'),
            htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8')
        ]);
        
        if($result){
            echo '<h3>Form submitted successfully!</h3>';
            echo '<p><a href="view_thread.php?id=' . htmlentities($_POST['thread_id'], ENT_QUOTES, 'UTF-8') . '">View Thread</a></p>';
            log_event($db, "Edited Thread", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "Edited thread with title: " . htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'));
        } else {
            $errorsql = $stmt->errorCode();
            echo 'errorcode ' . htmlentities($errorsql, ENT_QUOTES, 'UTF-8');
            echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
            log_event($db, "Edit thread failed", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "Editing thread failed with title: " . htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8') . ' errorcode ' . htmlentities($errorsql, ENT_QUOTES, 'UTF-8'));
        }

    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>New Thread Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a newly created thread" />
  </head>
  <body>
  
  </body>
</html>
