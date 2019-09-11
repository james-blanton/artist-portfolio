<?php
/*-------------------------------------------
FILE PURPOSE

Sends recovery password email.
Password recovery follows these steps:
(a) The user enters their username in to the input field.
(b) Click "Show Security Question" submit button.
(c) The security question tied to that username's account is shown to the user.
(d) The user enters the answer to their security question in to the "answer" text field.
(e) The user clicks "Recover Password"
(f) The user's password is reset to a random string in the database. 
(f) An email is sent to the email address tied to the account. This email contains the user's new password.

/*------------------------------------------*/

include("../header.php");

// database connection file
Include("connect.php");

// security question check

if(isset($_POST['forgot'])) {
	// make sure the input forms arent empty
	if (($_POST['newpass'] !=='') && ($_POST['username']) && ($_POST['answer'])) {

		$username = mysqli_real_escape_string($dbcon, $_POST['username']);
		$new_pass  = mysqli_real_escape_string($dbcon, $_POST['newpass']);
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
					// A select statement  to get the emain address tied to the account
					$sql = "SELECT email FROM admin WHERE username = '$username'";
					// executes a prepared query and stores the result as a result set or FALSE
					$result = mysqli_query($dbcon, $sql);

					while ($row = mysqli_fetch_assoc($result)) {
						// pull the email from the database tied to the user's account
						$email = $row['email'];

						// generate reset key for the user and submit it to the database
						$uniqidStr = md5(uniqid(mt_rand()));;
						$salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
						$generated_key = crypt($uniqidStr, $salt);
						// object oriented style prepared statement to update database row related to appropriate art category
						$stmt = $dbcon->prepare("UPDATE admin SET reset_key=? WHERE email=?");
						// binds variables to a prepared statement as parameters
						$stmt->bind_param('ss', $generated_key, $email);
						// executes a prepared query and stores the result as TRUE or FALSE
						$status = $stmt->execute();

						$reset_url = 'reset_password.php?key='.$generated_key.'&email='.$email.'&newpass='.$new_pass.'&action=reset';

						// Send the email to confirm the password reset to the portfolio admin
						// Email headers
						$to = $email; 
						$from = $email; 
						$fromName = 'Portfolio Admin Toolkit'; 
						$subject = "Portfolio Password Reset"; 

						    $htmlContent = ' 
						    <html>
							<head>
							<style type="text/css">
							body, html {
								background: #F1F1F1;
								width:100%;
								height:100%;
							}

							h1 {
								font-family: "futura-pt", "Segoe UI", "Helvetica Neue", Arial, sans-serif !important;
								text-transform: uppercase !important;
								text-decoration: none !important;
								letter-spacing: 0px !important;
								font-weight: 300 !important;
								font-size: 35px !important;
							}

							h2 {
								font-family: "futura-pt", "Segoe UI", "Helvetica Neue", Arial, sans-serif !important;;
								margin-top: 10px;
								margin-bottom: 10px;
								font-weight: 300 !important;;
								font-size: 25px !important;
								text-transform: uppercase;
							}

							.container_outerContent {
								padding-bottom: 2rem;
								color: #040404;
								background-color: #F1F1F1;
								height:100%;
								padding:3%;
							}

							.container_innerContent {
								width:80%;
								bottom: 0;
								position: relative;
								background-color: #FFFFFF;
								z-index: 100;
								margin-left: auto;
								margin-right: auto;
								padding: 15px;
								padding-top: 100px;
								box-shadow: 12px 0 15px -4px rgba(154, 154, 154, 0.8), -12px 0 8px -4px #9E9E9E;
							}

							.small, small {
								font-size:60% !important;
							}

							</style>

							</head>
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
							<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
							<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

							<body>

								<div class="container_outerContent">
									<div class="container_innerContent" style="padding:20px;">
										<p>It looks like you requested a password reset for your portfolio. Copy this url in to your address bar following your portfolio blog directory in order to finish the password reset: <a href="'.$reset_url.'">'.$reset_url.'</a></p>
										<h2><small><?php echo $email_from; ?></small></h2>
									</div>
								</div>

							</body>
							</html>
							';
						 
						// Set content-type header for sending HTML email 
						$headers = "MIME-Version: 1.0" . "\r\n"; 
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
						 
						// Additional headers 
						$headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n"; 
						 
						// Send email 
						if(mail($to, $subject, $htmlContent, $headers)){ 
						    echo 'Email has sent successfully.'; 
						}else{ 
						   echo 'Email sending failed.'; 
						}

						// redirect the user to the login page 
						// pass the 'pending' status via the url in order to display a feedback message to the  use letting them know that the password reset is pending
						// until they check their email and click on the appropriate link to confirm the reset
						Redirect('login?status=pending', false);
					}

				} else {
					$message = "Enter a username to recover your password.";
				}
					
			}
		}
	} else { Redirect('login?status=empty', false); }
} else {
	// This message will be displayed before the user has submitted any information through the input forms.
	$message = 'Enter your username and click "Show security question". <br/><br/>
			Fill in your username once more along with the answer to your security question and then click "Forgot password".<br/><br/>
			If the answer to your security question was correct, then you will be emailed a new password.<br/><br/>';

?>

	<div class ="w3-container w3-light-grey"><h2>Login</h2></div>

	<?php echo $message; ?>

	<?php
	if(isset($_POST['question'])){
		// This block of code will display the security question tied to the account
		// after the user enters a username and clicks "show security question"
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
		<label>Desired New Password</label>
		<input type="text" name ="newpass" class="w3-input w3-border">


		<input type="submit" name ="question" value="Show Security Question" class="w3-btn w3-light-grey"><br/><br/>
		<input type="submit" name ="forgot" value="Recover Password" class="w3-btn w3-light-grey">

	</form>

<?php
}
Include("footer.php"); 
?>