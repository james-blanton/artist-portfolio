<?php
/*-------------------------------------------
FILE PURPOSE

This file is for creating a new artwork category.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

?>

<div class="w3-container w3-light-grey">
<h2>Create New Artwork Category</h2></div>
<div class="w3-container">

<a href="admin"> << return to Admin Dashboard</a><br/>

<?php
// check if the form has been submitted yet
if(isset($_POST['submit'])) {
	// place data obtained from the form in to more usable variables
	$category_name = mysqli_real_escape_string($dbcon, $_POST['catname']);
	$filename = mysqli_real_escape_string($dbcon, $_POST['catname']);
	$filename = strtolower($filename);
	$description = mysqli_real_escape_string($dbcon, $_POST['description']);
	$display = mysqli_real_escape_string($dbcon, $_POST['header_display']);

	// Prepared query statement to insert all of the data for the new artwork catagory in to the 'category_artwork' table
	$sql = "INSERT INTO category_artwork (catname, filename, description, header_display) VALUES (?,?,?,?);";

	// initializes a statement and returns an object for use with mysqli_stmt_prepare
	$stmt  = mysqli_stmt_init($dbcon);

	// procedural style prepare statement
	// prepare an sql statement for execution
	// The query used here must be a string. It must consist of a single SQL statement.
	// mysqli_statement_prepare returns TRUE or FALSE
	if(!mysqli_stmt_prepare($stmt, $sql)){

		$user_messages = "ERROR: Not able to execute sql. ";

	} else {
		// binds variables to a prepared statement as parameters
		// s = corresponding variable has type string
		// i = 	corresponding variable has type integer
		mysqli_stmt_bind_param($stmt, "sssi", $category_name, $filename, $description, $display);
		mysqli_stmt_execute($stmt);

		// Get the id for the last ID submitted to the database so if can be used for a redirect link
		$lastid = mysqli_insert_id($dbcon); 

		echo '<br/>Artwork category database entry created successfully.<br/><br/>';	

		/*
		This section of code will create a php file with the same name as the category name that the admin entered.
		The file is created in the root directory of the portfolio.
		The code that is included matches the painting.php and drawing.php files with the name of the category being inserted appropriately in to the file. 
		*/
		$my_file = $filename.'.php';
		$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/'.$portfolio_directory.'/'.$my_file, 'w') or die('Cannot open file:  '.$my_file);
		$type='$type';
		$function_data = newCategory_include($filename,$type,$category_name);
		$data = $function_data;

		fwrite($handle, $data);
	}
}
else {
?>
		

<?php // user input form for submitting a new article / blog post ?>
<form class="w3-container" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
<label>Category Name</label>

<input type="text" class="w3-input w3-border" name="catname" required>
<br/>

<label>Description</label>
<textarea id = "mytextareaj" row="30" cols="50" class="w3-input w3-border large_textbox" name="description" required/></textarea>
<br/>

<label>Header Display</label><br/>
<select name="header_display" required>
        <option value='' disabled selected>Display Header</option>
        <option value="1">yes</option>
        <option value="0">no</option>
</select>
<br/><br/>

<input type="submit" class="w3-btn w3-light-grey w3-round" name="submit" value="Submit">
</form>
		
<?php
} 
include("footer.php");
?>
    
