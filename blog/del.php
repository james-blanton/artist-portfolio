<?php
/*-------------------------------------------
FILE PURPOSE

This file is meant to delete an existing blog / article post from the 'posts' table.
This file may not be accessed if the user is not logged in to an account.

Keep in mind that currently anyone who has an account in the database can access this file.
This file is executed on the admin dashboard page when a "delete" link is clicked, but this page itself does not actually display anything to the user.

I need to improve the error display at the bottom of this file. Right now I haven't set a direct location for this error display text. 

/*------------------------------------------*/

include("../header.php");

// database connection file
include("connect.php");

// Function to check that the id gathered from the url is valid. This function can be found in functions.php.
$id = $_GET['id'];
idCheck($id);

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

// check if an article / blog post ID has been recieved from the url or not
if(isset($_GET['id'])) {

	// object oriented style prepare statement
	// delete statement to remove the article / blog post information fron the database based on the id provided in the url
	$stmt = $dbcon->prepare("DELETE FROM posts WHERE id = ?");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param("i", $id);
	// executes a prepared query and stores the result as TRUE or FALSE
	$stmt->execute();

	// 
	if($stmt->affected_rows === 1) 
	{
		Redirect('edit_articles?e=deleted', false);
		exit();
	}
	else {
		Redirect('edit_articles?e=error', false);
		exit();
	}

}

mysqli_close($dbcon);
?>
 