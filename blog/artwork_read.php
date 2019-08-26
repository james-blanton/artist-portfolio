<?php
/*-------------------------------------------
FILE PURPOSE

This file processes artwork uploaded via the administrator backend control panel; it creates a database entry and uploads the file to the server.

The file includes error checking for the user input fields.  HTML forms have 'required' directive at the end as the first error check.
Currently I'm unsure on how to keep the html form from processing before checking if the file is of the appropriate size or not, so
for now this file will provide a warning message to the user in artwork_upload.php if the file size is too large.

This file won't allow an image upload larger than 4.60 MB (4,827,360 bytes).

The php.ini configuration file is limiting the max file size upload to ~1GB as well.

/*------------------------------------------*/

// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();
		
?>

<?php 
// Initialize the variable for displaying errors on artwork_upload to the user
$user_messages = '';

// IF block 1
// Check if the form from artwork_upload has been submitted or not
if(isset($_POST['submit'])) {
	// Initialize the variable for displaying errors on artwork_upload to the user
	$user_messages = '';

	// Don't allow files over 4.60 MB (4,827,360 bytes) to be uploaded
	if($_FILES['file']['size'] > 4827360){

	$user_messages = '<br/>File too large.';

	// Execute this else if the file is of the appropriate size (ends on line 124)
	} else {

		// Place data obtained from the form in to more usable variables
		$title = mysqli_real_escape_string($dbcon, $_POST['title']);
		$category = mysqli_real_escape_string($dbcon, $_POST['category']);
		$date = date("Y-m-d");
		$display = mysqli_real_escape_string($dbcon, $_POST['display']);

		// $_FILES is an associative array of items 
		$name= $_FILES['file']['name'];

		// Get the file type extension of the artwork being uploaded
		$position= strpos($name, "."); 
		$fileextension= substr($name, $position + 1);
		$fileextension= strtolower($fileextension);
		$tmp_name= $_FILES['file']['tmp_name'];

		// IF block 2 (ends on line 122)
		// Error check to make sure that the title and category field have been filled in
		// the title and the category for artwork are required user input fields
		if (($_POST['title'] !== '') && ($_POST['category'] !== '')){

			// Check if the file has been selected for upload through the html form (ends on line 119)
			if (isset($name)) {

				// Set the path for what directory the artwork file will be stored in after upload
				$path= '../artwork/';

				// Check the file extension and exit the file if the file type is not appropriate
				if (($fileextension !== "jpeg") && ($fileextension !== "jpg") && ($fileextension !== "png"))
				{
					$user_messages = "The file extension must be .jpeg .jpg, or .png in order to be uploaded";
					exit();
				}

				// IF block 4 (ends on line 116)
				// The file type MUST be appropriate at this point, but this is a good secondary security check
				else if (($fileextension == "jpeg") || ($fileextension == "jpg") || ($fileextension == "png"))
				{
					// IF block 5
					// Check if the file was successfully uploaded or not & if it has been, then insert the information on the file in to the database.
					// If the image upload fails for some reason, then display an error to the user.
					if (move_uploaded_file($tmp_name, $path.$name)) 
					{
						$user_messages = '<br/>Uploaded!<br/>';
						// Procedural style prepared query statement to insert all of the data for the submitted artwork file in to the 'artwork' database table
						$sql = "INSERT INTO artwork (name, title, category, filedate, display_hide) VALUES (?,?,?,?,?);";
						// Initializes a statement and returns an object for use with mysqli_stmt_prepare
						$stmt  = mysqli_stmt_init($dbcon);

						// Prepare an sql statement for execution
						// The query used here must be a string. It must consist of a single SQL statement.
						// mysqli_statement_prepare returns TRUE or FALSE
						if(!mysqli_stmt_prepare($stmt, $sql)){

							$user_messages = "ERROR: Could not able to execute sql. " . mysqli_error($dbcon);

						} else {
							// binds variables to a prepared potoset as parameters
							// s = corresponding variable has type string
							// i = 	corresponding variable has type integer
							mysqli_stmt_bind_param($stmt, "ssssi", $name, $title, $category, $date, $display);

							// executes the prepared query (returns a TRUE or FALSE)
							mysqli_stmt_execute($stmt);

							$user_messages = "<br/>Artwork uploaded and data sent.<br/>";

						}

					// IF block 5 close
					} else $user_messages = '<br/>File upload failed.<br/>';

				// IF block 4 close
				}

			// IF block 3 close (line 64)
			}

		// IF block 2 close
		} else $user_messages= '<br/>Please enter artwork data.<br/>';
	// End file size else (line 40)
	}
// IF block 1 close	
} else { 
	$user_messages = '<br/>Select a file to upload. Title and category are required.<br/>';
}
?>
