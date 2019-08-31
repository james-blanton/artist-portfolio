<?php
/*-------------------------------------------
FILE PURPOSE

Deletes a blog category.

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
	$stmt = $dbcon->prepare("DELETE FROM category WHERE id = ?");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param("i", $id);
	// executes a prepared query and stores the result as TRUE or FALSE
	$stmt->execute();

	// Redirect the user to the index dashboard page of the blog / article directory once the deletion is complete.
	if($stmt->affected_rows === 1) 
	{
		Redirect('admin', false);
		exit();
	}
	else {
		// This error display needs to be improved. Right now I don't have a set location for where this error would appear.
		echo "Failed to delete.";
	}

}

mysqli_close($dbcon);
?>
 