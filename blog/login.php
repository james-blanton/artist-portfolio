<?php
/*-------------------------------------------
FILE PURPOSE

This file is for logging the user in. It runs a check to see if row exists in the database with a username and password that match what the user entered in the form.
Right now I am using the one way string hashing function.

Reference:
https://www.php.net/manual/en/function.crypt.php
https://www.php.net/manual/en/function.hash-equals.php

The syntax is as  follows: crypt(str, salt)

Parameters:
str − The string to be hashed. Required.
salt − Salt string to base the hashing on. Optional.

The crypt() function returns the encoded string and is guaranteed to differ from the salt on failure. 
The salt parameter is optional. However, crypt() creates a weak hash without the salt. In this scenario PHP will auto-generate either a standard two character (DES) salt, or a twelve character (MD5).

/*------------------------------------------*/

include("../header.php");

// database connection file
Include("connect.php");

if(isset($_POST['log'])) {
	// initialize variables
	$message = ""; 
	$check_true_false = "";

	// get the data entered in to the form by the user
	$theusername = mysqli_real_escape_string($dbcon, $_POST['username']);
	$password = mysqli_real_escape_string($dbcon, $_POST['password']);

	// perform the string hashing for the password entered in to the form by the user 
	$salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
	$username = $_POST['username'];
	$password = crypt($password, $salt);
    $status = "";

    // object oriented style prepare statement
    $stmt = $dbcon->prepare("SELECT id, username, password, email FROM admin WHERE username=? AND password=? LIMIT 1");
    // binds variables to a prepared statement as parameters
    $stmt->bind_param('ss', $username, $password);
    // executes a prepared query
    $stmt->execute();
    // binds variables to a prepared statement for result storage
    $stmt->bind_result($user_id, $username, $password, $email);
    // transfers a result set from a prepared statement
    $stmt->store_result();

    // check to see if the row called by the prepared statement exists
    if($stmt->num_rows == 1)  
    {
        if($stmt->fetch()) //fetching the contents of the row
        {
            if ($status == 'd') {
                $check_true_false = 'false';
            } else {
            	$check_true_false = 'true';
            }
        }
    }

	$stmt->close();

	// Set the session and redirect the user to the admin dashboard if their login was successful (the username & password entered match a datbase record)
	// If the form is submitted and the password entered in to the form does not match the password in the database for the respective username, then echo an error message and re-display the login form to the user.
	if($check_true_false == true) {
			$_SESSION['username'] = $username;
			Redirect('admin', false);
		} 
		else {
			$message = "Login information does not watch any records in our database. Please try again.<br/>";

?>
			<div class ="w3-container w3-light-grey"><h2>Login</h2></div>
			<?php 
			if(isset($_GET['status']) && $_GET['status'] == 'reset'){
				echo 'Password reset. Wait a moment before checking your email and attempting to log in.<br/><br/>';
			}
			?>

			<a href="forgot_pass.php">Forgot Password</a>?<br/><br/>

			<?php echo $message; ?>
			<form action ="" method ="POST" class="w3-container">
				<label>Username</label>
				<input type="text" name="username" class="w3-input w3-border">
				<label>Password</label>
				<input type="password" name ="password" class="w3-input w3-border">
				<input type ="submit" name ="log" value="Login" class="w3-btn w3-light-grey">
			</form>

<?php
		}
}  else {
	// if no login information has been submitted via the form yet, then display this login form and prompt to the user to attempt login
	$message = "Please enter login info.";
?>
	<div class ="w3-container w3-light-grey"><h2>Login</h2></div>

	<?php 
	if(isset($_GET['status']) && $_GET['status'] == 'reset'){
		echo 'Password reset. Wait a moment before checking your email and attempting to log in.<br/><br/>';
	}
	?>

	<a href="forgot_pass.php">Forgot Password</a>?<br/><br/>
	<?php echo $message; ?>
	<form action ="" method ="POST" class="w3-container">
		<label>Username</label>
		<input type="text" name="username" class="w3-input w3-border">
		<label>Password</label>
		<input type="password" name ="password" class="w3-input w3-border">
		<input type ="submit" name ="log" value="Login" class="w3-btn w3-light-grey">
	</form>

<?php
}
Include("footer.php"); 
?>