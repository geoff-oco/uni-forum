<?php
require 'db_connect.php';
	if(isset($_SESSION['uname'])){
		header('Location: list_threads.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <meta name="author" content="Geoffrey O'Connell" />
    <meta name="description" content="A form to register a new user" />
    <style>
        fieldset {width: 350px;}
    </style>
    <script>
        function validateForm() {
            // form variable name
            var form = document.forms["register_form"];
            var userName = form.uname;
			var namelength = userName.value.trim();
            var pass = form.pword;
			var passlength = pass.value.trim();
            var passConf = form.pword_conf;
            var dob = form.dob;
            var agreed = form.agree;
            var validation = true;
            var errorString = "";
			var alphanum = /^[a-z0-9]+$/i;
			var dateval = new Date(dob.value);
			var currentDate = new Date();
            var age = currentDate.getFullYear() - dateval.getFullYear();
            var monthDifference = currentDate.getMonth() - dateval.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && currentDate.getDate() < dateval.getDate())) {
                age--;
            }
			
            // Reset background color
            userName.style.backgroundColor = '';
            pass.style.backgroundColor = '';
            passConf.style.backgroundColor = '';
            dob.style.backgroundColor = '';
			agreed.style.backgroundColor = '';
            
            if (namelength.length > 20 || namelength.length < 6) {
                errorString += "The username must be between 6 and 20 characters only!\n";
                userName.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (!alphanum.test(userName.value.trim())) {
                errorString += "The username must only contain alpha numeric characters!\n";
                userName.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (passlength.length < 8) {
                errorString += "The password must be at least 8 characters!\n";
                pass.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (pass.value != passConf.value) {
                errorString += "Password and confirmation must be the same!\n";
                pass.style.backgroundColor = '#FFC8C8';
                passConf.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (dob.value == null || dob.value == '') {
                errorString += "Date of Birth cannot be empty.\n";
                dob.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (age < 14) {
                errorString += "New user must be at least 14.\n";
                dob.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (!agreed.checked) {
                errorString += "You must agree to terms and conditions.\n";
                agreed.style.backgroundColor = '#FFC8C8';
				if(validation == true){
					validation = false;
				}
            }
            if (validation == false) {
                alert(errorString);
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <h1>Create Account</h1>
    <p><a href="list_threads.php">List Threads</a> | <a href="search_threads.php">Search Threads</a></p>
    <form method="post" name="register_form" action="register.php" onsubmit="return validateForm()">
        <fieldset><legend>User Credentials</legend>
            <p><input type="text" name="uname" placeholder="Username" title="Username" /></p>
            <p>
                <input type="password" name="pword" placeholder="Password" title="Password" />
                <input type="password" name="pword_conf" placeholder="Confirm password" title="Confirm password" />
            </p>
        </fieldset>
      
        <fieldset><legend>Other Details</legend>
            <p><input type="text" name="rname" placeholder="Real Name" title="Realname" /></p>
            <p><input type="date" name="dob" placeholder="dd/mm/yyyy" title="DateOfBirth" /></p>
            <p><input type="checkbox" name="agreed" value = "yes"/> I agree to all terms and conditions</p>
            <p><input type="submit" name="submit"></p>
        </fieldset>
    </form>
</body>
</html>