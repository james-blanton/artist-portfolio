<?php
/*-------------------------------------------
FILE PURPOSE

This file is for logging the user in. It runs a check to see if row exists in the database with a username and password that match what the user entered in the form.
Right now we are using the one way string hashing function.

Reference:
https://www.php.net/manual/en/function.crypt.php
https://www.php.net/manual/en/function.hash-equals.php

The syntax is as follows: crypt(str, salt)

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

	// Set the session and redirect the user to the admin dashboard if their login was successful (the username & password entered match a database record)
	// If the form is submitted and the password entered in to the form does not match the password in the database for the respective username, then echo an error message and re-display the login form to the user.
	if($check_true_false == true) {
			$_SESSION['username'] = $username;
			Redirect('admin', false);
		} 
		else {
			$message = "Login information does not watch any records in our database. Please try again.<br/>";

?>
			<div class ="w3-container w3-light-grey"><h2>Login</h2></div>
			<?php echo $message; ?>
			<form action ="" method ="POST" class="w3-container">
				<label>Username</label>
				<input type="text" name="username" class="w3-input w3-border">
				<label>Password</label>
				<input type="password" name ="password" class="w3-input w3-border">
				<input type ="submit" name ="log" value="Login" class="w3-btn w3-light-grey">
				<input type="submit" name ="forgot" value="Forgot Password" class="w3-btn w3-light-grey">
			</form>

<?php
		}
}  else {
	// if no login information has been submitted via the form yet, then display this login form and prompt to the user to attempt login
	$message = "Please enter login info.";
		?>

	<?php
	// password recovery  begins after the user clicks the 'forgot password' button
	if(isset($_POST['forgot'])){
		// don't attempt to recover the password unless the user has entered a username
		if(!empty($_POST['username'])){
			$username = mysqli_real_escape_string($dbcon, $_POST['username']);
			$sql = "SELECT * FROM `admin` WHERE username = '$username'";
			$res = mysqli_query($dbcon, $sql);
			$count = mysqli_num_rows($res);

			if($count == 1){
				//generat unique string
	            $uniqidStr = md5(uniqid(mt_rand()));;
	            $salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
				$generated_password = crypt($uniqidStr, $salt);
			    // object oriented style prepared statement to update database row related to appropriate art category
			    $stmt = $dbcon->prepare("UPDATE admin SET password=? WHERE username=?");
			    // binds variables to a prepared statement as parameters
			    $stmt->bind_param('ss', $generated_password, $username);
			    // executes a prepared query and stores the result as TRUE or FALSE
			    $status = $stmt->execute();
            
	            if($status = 1){
	            	$message = "Password reset.";

	            	// email the new password stored in the $uniqidStr variable  to the user

					// object oriented style prepare statement
					$username = mysqli_real_escape_string($dbcon, $_POST['username']);
					// A select statement 
					$sql = "SELECT email FROM admin WHERE username = '$username'";
					// executes a prepared query and stores the result as a result set or FALSE
					$result = mysqli_query($dbcon, $sql);

					// Store the blog post information returned from the database query in variables and .
					while ($row = mysqli_fetch_assoc($result)) {
						$email = $row['email'];

						// the message
						$msg = "It looks like you have requested a password reset for your portfolio.\nYour new password is ".$uniqidStr;

						// send email
						mail($email,"Password Reset",$msg);
			        }
	            }

                /* end reset */
			}else{
				$message = "User name does not exist in database.";
			}
		} else {
			$message = "Enter a username to recover your password.";
		}
	}
	?>

	<div class ="w3-container w3-light-grey"><h2>Login</h2></div>
	<?php echo $message; ?>
	<form action ="" method ="POST" class="w3-container">
		<label>Username</label>
		<input type="text" name="username" class="w3-input w3-border">
		<label>Password</label>
		<input type="password" name ="password" class="w3-input w3-border">
		<input type ="submit" name ="log" value="Login" class="w3-btn w3-light-grey"><br/><br/>
		<input type="submit" name ="forgot" value="Forgot Password" class="w3-btn w3-light-grey">
	</form>
<?php
}
Include("footer.php"); 
?>