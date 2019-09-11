<?php
// database connection file
Include("connect.php");

include("../header.php");

?>

<div class ="w3-container w3-light-grey"><h2>Login</h2></div>

<?php

if (isset($_GET["key"]) && ($_GET["key"]!=="") && isset($_GET["email"]) && isset($_GET["action"]) && isset($_GET["newpass"]) && ($_GET["action"]=="reset") && !isset($_POST["action"])){
	$reset_key = mysqli_real_escape_string($dbcon, $_GET["key"]);
	$email = mysqli_real_escape_string($dbcon, $_GET["email"]);
	$sql = "SELECT * FROM `admin` WHERE email = '$email' AND reset_key = '$reset_key'";
	$res = mysqli_query($dbcon, $sql);
	$count = mysqli_num_rows($res);

	if($count == 1){

		//generat unique string
		$textToEncrypt = $_GET["newpass"];
		$salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
		$encryptedPassword = crypt($textToEncrypt, $salt);

		// object oriented style prepared statement to update database row related to appropriate art category
		$stmt = $dbcon->prepare("UPDATE admin SET password=? WHERE email=?");
		// binds variables to a prepared statement as parameters
		$stmt->bind_param('ss', $encryptedPassword, $email);
		// executes a prepared query and stores the result as TRUE or FALSE
		$status = $stmt->execute();

		// change user feedback message
		$msg = "Your new password reset was a success.";

		// Send the email to confirm the password reset to the portfolio admin
		// Email headers
		$to = $email; 
		$from = $email; 
		$fromName = 'Portfolio Admin Toolkit'; 
		$subject = "Portfolio Password Reset"; 
		
		// Email body	 
		$htmlContent = ' 
		<html>
		<head>
		style type="text/css">
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
				<p>Your password has been successfully reset!</p>
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
		    echo 'Email has sent successfully.<br/><br/>'; 
		}else{ 
		   echo 'Email sending failed.'; 
		}

		$empty = '';
		// object oriented style prepared statement to update database row related to appropriate art category
		$stmt = $dbcon->prepare("UPDATE admin SET reset_key=? WHERE email=?");
		// binds variables to a prepared statement as parameters
		$stmt->bind_param('ss', $empty, $email);
		// executes a prepared query and stores the result as TRUE or FALSE
		$status = $stmt->execute();
		
		echo 'Password reset was a success.<br/><br/>Return to <a href="login">login</a>';
		
	} else { Redirect('login?status=error', false); }
} 
else {
	Redirect('login?status=error', false);
}
?>

<?php
Include("footer.php"); 
?>