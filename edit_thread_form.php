<?php
  require 'db_connect.php';
  
  if(isset($_SESSION['uname'])){
	  $logged_in = true;
	  echo 'Welcome '.htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8').' your access level is '.htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8').' ';
	  echo '<a href="logout.php">Logout</a>';
  }
  else{
	  header('Location: login.php');
  }
  
  if (!isset($_GET['id']) || !ctype_digit($_GET['id']))
  { // If there is no "id" URL data or it isn't a number
    header("Location: list_threads.php");
    exit;
  }
  
  $stmt = $db->prepare("SELECT * FROM thread WHERE thread_id = ? AND username = ?");
  $stmt->execute([htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'), htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8')]);
  $thread = $stmt->fetch();
  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Forum Thread</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to edit a new thread to the column" />
    <script>
        function validateForm() {
            // form variable name
            var form = document.forms["edit_thread"];
            var title = form.title;
            var content = form.content;
            var forum = form.forum;
            var validation = true;
            var errorString = "";
            
            // Reset background color
            title.style.backgroundColor = '';
            content.style.backgroundColor = '';
            forum.style.backgroundColor = '';
            
            if (title.value.trim() == "") {
                errorString += "The post needs a title.\n";
                title.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            if (content.value.trim() == "") {
                errorString += "The post needs some content.\n";
                content.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            if (forum.value == "") {
                errorString += "The post needs to be assigned to a forum.\n";
                forum.style.backgroundColor = '#FFC8C8';
                validation = false;
            }
            
            // validate
            if (!validation) {
                alert(errorString);
                return false;
            }
            return true; // Allow form submission if validation passes
        }
    </script>

</head>

<body>
    <h1>Edit Thread</h1>
	<p><a href="search_threads.php">Search</a> | <a href="list_threads.php">List Threads</a></p>
    <p>What do you want to post about today?</p>
    
    <form name="edit_thread" method="post" action="edit_thread.php" onsubmit="return validateForm()">
        <p><strong>Title:</strong><br />
            <textarea name="title" style="width: 600px; height: 50px"><?= htmlspecialchars($thread['title'], ENT_QUOTES, 'UTF-8') ?></textarea>
        </p>
        
	<p><strong>Content:</strong><br />
		<textarea name="content" style="width: 600px; height: 200px"><?= htmlspecialchars($thread['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
	</p>

        
        <p><strong>Select Forum:</strong><br />
            <select name="forum">
                <option value="" selected disabled>Select a Forum</option>
                <?php  
                // Select details of all forums
                $result = $db->query("SELECT * FROM forum ORDER BY forum_id");
      
                // Loop through each forum to generate an option of the drop-down list
                foreach($result as $row)
                {
                    $text = $thread['forum_id']==$row['forum_id'] ? 'selected' : '';
                    echo '<option value="'.htmlentities($row['forum_id'], ENT_QUOTES, 'UTF-8').'" '.$text.'>'.htmlentities($row['forum_name'], ENT_QUOTES, 'UTF-8').'</option>';
                }
                ?>
            </select>
        </p>
		
		<input type="hidden" id="thread_id" name="thread_id" value="<?= htmlentities($thread['thread_id'], ENT_QUOTES, 'UTF-8') ?>">

        <p>
            <input type="submit" name="submit"/>
        </p>
    </form>
</body>
</html>
