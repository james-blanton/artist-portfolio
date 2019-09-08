<?php
/*-------------------------------------------
FILE PURPOSE

Deletes a artwork category.

/*------------------------------------------*/

include("../header.php");

// database connection file
include("connect.php");

// Function to check that the id gathered from the url is valid. This function can be found in functions.php.
$id = $_GET['id'];
$filename = $_GET['filename'];
idCheck($id);

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

// check if an article / blog post ID has been recieved from the url or not
if(isset($_GET['id'])) {

	// object oriented style prepare statement
	// delete statement to remove the art category information fron the database based on the id provided in the url
	$stmt = $dbcon->prepare("DELETE FROM category_artwork WHERE id = ?");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param("i", $id);
	// executes a prepared query and stores the result as TRUE or FALSE
	$stmt->execute();


	// Redirect the user to the index dashboard page if the delete was successful.
	if($stmt->affected_rows === 1) 
	{
		/*
		The root directory variable is also set in config.php.
		As an example, if you ran this portfolio an a local XAMPP server and you placed the files within xampp > htdocs > artist-portfolio,
		then you would need to set the $root_directory variable to 'false' because the 'htdocs' folder is the root directory and the 'artist-portfolio' folder is nested within it.
		In this example, the portfolio would need to be accessed by appending the localhost url with 'artist-portfolio' like so:
		https://localhost/artist-portfolio/index
		*/
		if($root_directory == false){
			// delete the category file from the nested directory
			if (!unlink($_SERVER['DOCUMENT_ROOT'].'/'.$portfolio_directory.'/'.$filename)) {
				Redirect('art_category_manage?e=error', false);
				exit();
			} else {
			  	Redirect('art_category_manage?e=deleted', false);
				exit();
			}
		} else
		{
			// delete the category file from the portfolio root directory
			if (!unlink($_SERVER['DOCUMENT_ROOT'].'/'.$filename)) {
				Redirect('art_category_manage?e=error', false);
				exit();
			} else {
			  	Redirect('art_category_manage?e=deleted', false);
				exit();
			}
		}

		// redirect the admin to the admin control panel
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
 