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

		// send email with new password
		// send the email to confirm the password reset to the portfolio admin
		// the body of the email
		$msg = "Your new password reset was a success.";

		// send email
		mail($email,"Password Reset",$msg);

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