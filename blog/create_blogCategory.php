<?php
/*-------------------------------------------
FILE PURPOSE

Create a new category for blog posts.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

?>

<div class="w3-container w3-light-grey">
<h2>Create Blog Category</h2></div>
<div class="w3-container">

<a href="admin"> << return to Admin Dashboard</a><br/>

<?php
// check if the form has been submitted yet
if(isset($_POST['submit'])) {
	// place data obtained from the form in to more usable variables
	$title = mysqli_real_escape_string($dbcon, $_POST['title']);
	$description = mysqli_real_escape_string($dbcon, $_POST ['description']); 

	// Prepared query statement to insert all of the data for the submitted blog category in to the 'category' database table
	$sql = "INSERT INTO category (catname, description) VALUES (?,?);";

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
		mysqli_stmt_bind_param($stmt, "ss", $title, $description);
		mysqli_stmt_execute($stmt);

		// Get the id for the last ID submitted to the database so if can be used for a redirect link
		$lastid = mysqli_insert_id($dbcon); 

		echo '<br/>New blog category created successfully.<br/> <br/> 
		<a href="cat?id='.$lastid.'">Go to new blog category</a><br/>';	
	}
}
else {
?>
		

<?php // user input form for submitting a new article / blog post ?>
<form class="w3-container" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
<label>Title</label>

<input type="text" class="w3-input w3-border" name="title" required>
<br>

<label>Description</label>
<textarea id = "mytextareaj" row="30" cols="50" class="w3-input w3-border large_textbox" name="description" required/></textarea>
<br>

<input type="submit" class="w3-btn w3-light-grey w3-round" name="submit" value="Submit">
</form>
		
<?php
} 
include("footer.php");
?>
    
