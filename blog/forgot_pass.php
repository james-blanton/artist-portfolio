<?php
/*-------------------------------------------
FILE PURPOSE

Sends recovery password email.

/*------------------------------------------*/

include("../header.php");

// database connection file
Include("connect.php");

// security question check
if(isset($_POST['forgot'])){
	$username = mysqli_real_escape_string($dbcon, $_POST['username']);
	$answer =mysqli_real_escape_string($dbcon, $_POST['answer']);
	$sql = "SELECT * FROM `admin` WHERE username = '$username' and security_answer = '$answer'";
	$res = mysqli_query($dbcon, $sql);
	$count = mysqli_num_rows($res);

	// if the security question was answeered correctly, then send the reset
	if($count == 1){

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
							Redirect('login?status=reset', false);
				        }
		            }

		               
				} else {
					$message = "User name does not exist in database.";
				}
			} else {
				$message = "Enter a username to recover your password.";
			}
				
		}
	}
} else {
	$message = 'Enter your username and click "Show security question". <br/>
			Fill in your username once more along with the answer to your security question and then click "Forgot password".<br/>
			If the answer to your security question was correct, then you will be emailed a new password.<br/><br/>';

?>

	<div class ="w3-container w3-light-grey"><h2>Login</h2></div>

	<?php echo $message; ?>

	<?php
	if(isset($_POST['question'])){
		$username = mysqli_real_escape_string($dbcon, $_POST['username']);
		$sql = "SELECT security_question FROM admin WHERE username = '$username'";
		// executes a prepared query and stores the result as a result set or FALSE
		$result = mysqli_query($dbcon, $sql);

		if(mysqli_num_rows($result) > 0){
			// 
			while ($row = mysqli_fetch_assoc($result)) {
				$question = '<b>Security question</b>: '.$row['security_question'];
				echo $question;
			}
		} else {
			$question = 'Please follow the proper steps in order to recover your password.';
			echo $question;
		}
	}

	?>
	<form action ="" method ="POST" class="w3-container">
		<label>Username</label>
		<input type="text" name="username" class="w3-input w3-border">
		<label>Security Question Answer</label>
		<input type="text" name ="answer" class="w3-input w3-border">

		<input type="submit" name ="forgot" value="Forgot Password" class="w3-btn w3-light-grey">
		<input type="submit" name ="question" value="Show Security Question" class="w3-btn w3-light-grey">
	</form>

<?php
}
Include("footer.php"); 
?>