<?php
/*-------------------------------------------
FILE PURPOSE

This file is meant to delete an existing entry in the 'artwork' table related to a artwork image upload. It also deletes the files itself from a "artwork" directory found within the root directory.

This file may not be accessed if the user is not logged in to an account.

Keep in mind that currently anyone who has an account in the database can access this file.

This file is executed on the admin dashboard page when a "delete" link is clicked for a piece of artwork, but this page itself does not actually display anything to the user.

I need to improve the error display at the bottom of this file. Right now I haven't set a direct location for this error display text. 

/*------------------------------------------*/

include("../header.php");

// Database connection file
include("connect.php");
// Get the filename to delete it from the admin table if its the icon image.
$filename = $_GET['filename'];
// Function to check that the id gathered from the url is valid.
$id = $_GET['id'];
idCheck($id);

// Function to ensure that the user is logged in as an admin.
loginCheck();

// Check if the artwork ID has been recieved from the url or not.
// If the url has been recieved, then perform the delete.
if(isset($_GET['id'])) {
	// check if the image being deleted is a header icon image
	// if it is, then clear the 'logo_flename' field in the general_info table before deleting the image
	// this will make it so there's no logo image at all in the header
	
	// Object oriented style prepare statement to determine the file name for the artwork that has this unique ID in the database.
	$stmt = $dbcon->prepare("SELECT info FROM general_info WHERE info = ?");
	// Binds variables to a prepared statement as parameters.
	$stmt->bind_param("s", $filename);
	// Executes a prepared query.
	$stmt->execute();
	// Transfers a result set from a prepared statement.
	$stmt->store_result();
	// Count the number of rows returned by the query.
	$numrows = $stmt->num_rows;

	$empty = NULL;
	if($numrows > 0){
	$stmt = $dbcon->prepare("UPDATE general_info SET info = NULL WHERE field_name = 'logo_filename'");
	$stmt->execute();
	}



	//

	// Object oriented style prepare statement to determine the file name for the artwork that has this unique ID in the database.
	$stmt = $dbcon->prepare("SELECT name FROM artwork WHERE id = ?");
	// Binds variables to a prepared statement as parameters.
	$stmt->bind_param("i", $id);
	// Executes a prepared query.
	$stmt->execute();
	// Transfers a result set from a prepared statement.
	$stmt->store_result();
	// Count the number of rows returned by the query.
	$numrows = $stmt->num_rows;

	// Display error if there's no row returned to delete artwork entry.
	if($numrows === 0) exit('No rows returned.');

	if($numrows > 0) {
		// Bind result variables.
		$stmt->bind_result($filename);

		while ($stmt->fetch()) {
			// Delete the file itself.
			unlink('../artwork/'.$filename);

			// Object oriented style prepare statement. Query the entry in the database related to the artwork file. 	
			$stmt = $dbcon->prepare("DELETE FROM artwork WHERE id = ?");
			// Binds variables to a prepared statement as parameters.
			$stmt->bind_param("i", $id);
			// Executes a prepared query and stores the result as TRUE or FALSE.
			$stmt->execute();

			// If the deletion of the database row was successful, then redirect the user back to the admin dashboard.
			if($result) {
				Redirect('admin', false);
				exit();
			}
			else {
				// This error display needs to be improved. Right now I don't have a set location for where this error would appear.
				echo "Failed to delete.";
			}
		}

		mysqli_close($dbcon);
	}
}

// Redirect the user to the admin panel if they attempt the access this file without having a artwork ID set in the url.
Redirect('admin', false);
?>

<?php

Include("footer.php"); 
?>