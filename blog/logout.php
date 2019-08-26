<?php
/*-------------------------------------------
FILE PURPOSE

This file logs out the user / administrator and re-directs them to the login page.
If the user tries to access the logout file for some reason when they aren't even logged in, then they get redirected to the login page.

/*------------------------------------------*/

include("../header.php");

// Function to ensure that the user is logged in as an admin. This function can be found in header.php.
loginCheck();

if(!isset($_SESSION['username'])) 
{
	header("location: login");
}
else
{
	session_destroy();
	header("location: login") ;
}

?>