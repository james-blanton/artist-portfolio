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

	// 
	if($stmt->affected_rows === 1) 
	{
		Redirect('blog_category_manage?e=deleted', false);
		exit();
	}
	else {
		Redirect('blog_category_manage?e=error', false);
		exit();
	}

}

mysqli_close($dbcon);
?>
 