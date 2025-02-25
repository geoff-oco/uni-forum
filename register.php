<?php
require 'db_connect.php';

if(isset($_SESSION['uname'])){
    header('Location: list_threads.php');
}

// This code checks if the $_POST variable (which contains form data submitted using the POST method)
// contains a key of 'submit' (the name of the submit button), and if so it prints the form data.
if (isset($_POST['submit']))
{
    $errorMessages = array();
    $age;
    $uname = $_POST['uname'];
    $pass = $_POST['pword'];
    $passConf = $_POST['pword_conf'];
    $dob = $_POST['dob'];
    $agreed = isset($_POST['agreed']) && $_POST['agreed'] === 'yes';

    // Check for validation errors
    if(trim($uname) == "" || trim(strlen($uname)) > 20 || trim(strlen($uname)) < 6){
        array_push($errorMessages, "The username must be between 6 and 20 characters only!\n");
    }
    if(ctype_alnum($uname) == false){
        array_push($errorMessages, "The username must only contain alpha numeric characters!\n");
    }
    if(trim($pass) == "" || trim(strlen($pass)) < 8){
        array_push($errorMessages, "The password must be at least 8 characters!\n");
    }
    if(trim($pass) != trim($passConf)){
        array_push($errorMessages, "Password and confirmation must be the same!\n");
    }
    if(empty($dob)){
        array_push($errorMessages, "The date of Birth must be filled in\n");
    }
    else{
        $age = date_diff(date_create($dob), date_create("now"))->y;
        if($age < 14){
            array_push($errorMessages,'You are too young to register an account for this forum');
        }
    }
    if(!$agreed){
        array_push($errorMessages, "You must agree to terms and conditions\n");
    }

    if(!empty($errorMessages)){
        // Display error messages and link to previous page
        foreach($errorMessages as $a){
            echo '<p>' . htmlentities($a, ENT_QUOTES, 'UTF-8') . '</p>';
        }
    } else {
        $hash = password_hash(trim($pass), PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO user (username, password, real_name, dob) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([
            htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8'),
            $hash,
            htmlentities($_POST['rname'], ENT_QUOTES, 'UTF-8'),
            htmlentities($_POST['dob'], ENT_QUOTES, 'UTF-8')
        ]);
        
        if($result){
            echo '<h3>Form submitted successfully!</h3>';
            echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
            log_event($db, "Registration", htmlentities($uname, ENT_QUOTES, 'UTF-8'), "Registration successful" . htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword_conf'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['dob'], ENT_QUOTES, 'UTF-8'));
        } else {
            $errorsql = $stmt->errorCode();
            if($errorsql == '23000'){
                echo 'That username already exists man';
                echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
                log_event($db, "Registration", htmlentities($uname, ENT_QUOTES, 'UTF-8'), "Duplicate username" . htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword_conf'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['dob'], ENT_QUOTES, 'UTF-8'));
            } else {
                echo 'Insertion failed with code ' . htmlentities($errorsql, ENT_QUOTES, 'UTF-8');
                echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
                log_event($db, "Registration", htmlentities($uname, ENT_QUOTES, 'UTF-8'), "Registration failed" . htmlentities($_POST['uname'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['pword_conf'], ENT_QUOTES, 'UTF-8') . " " . htmlentities($_POST['dob'], ENT_QUOTES, 'UTF-8') . 'errorcode: ' . htmlentities($errorsql, ENT_QUOTES, 'UTF-8'));
            }
        }

    }
} else {
    echo '<p>Please submit form data</p>';
    echo '<p><a href="javascript:history.back()">Go back to previous</a></p>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Handler</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to handle a new registration" />
</head>
<body>

</body>
</html>
