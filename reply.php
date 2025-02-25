<?php
require 'db_connect.php';

if(!isset($_SESSION['uname'])){
    header('Location: login.php');
} else {
    if (isset($_POST['submit'])) {
        $reply = $_POST['reply'];
        $thread_id = $_POST['thread_id'];
        $errorMessages = array();

        if(trim($reply) == ""){
            array_push($errorMessages, "The reply can't be blank!\n");
        }
        if(!ctype_alnum($thread_id) || $thread_id == ''){
            array_push($errorMessages, "The reply needs a thread to go with it!\n");
        }
        if(!empty($errorMessages)){
            // Display error messages and link to previous page
            foreach($errorMessages as $a){
                echo '<p>' . htmlentities($a, ENT_QUOTES, 'UTF-8') . '</p>';
            }
            echo '<p><a href="list_threads.php"></a><br />';
        } else {
            $stmt = $db->prepare("INSERT INTO reply (username, thread_id, content) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'),
                htmlentities($thread_id, ENT_QUOTES, 'UTF-8'),
                htmlentities($reply, ENT_QUOTES, 'UTF-8')
            ]);
            header('Location: view_thread.php?id=' . htmlentities($thread_id, ENT_QUOTES, 'UTF-8'));

            if($result){
                echo '<h3>Form submitted successfully!</h3>';
                echo '<p><a href="view_thread.php?id=' . htmlentities($db->lastInsertId(), ENT_QUOTES, 'UTF-8') . '">View Thread</a></p>';
                log_event($db, "New Reply", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "New reply created to post: " . htmlentities($thread_id, ENT_QUOTES, 'UTF-8'));
            } else {
                echo htmlentities($stmt->errorCode(), ENT_QUOTES, 'UTF-8');
                echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
                log_event($db, "Failed reply", htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8'), "New reply creation failed to post: " . htmlentities($thread_id, ENT_QUOTES, 'UTF-8'));
            }
        }
    }
}
?>
