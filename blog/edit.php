<?php
/*-------------------------------------------
FILE PURPOSE

This file is found via the admin dashboard. In short, this is used to edit existing blog posts / articles. All of the content edited in this file is related to the "posts" table.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to check that the id gathered from the url is valid. This function can be found in functions.php.
$id = $_GET['id'];
idCheck($id);

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

// redirect the user away if they try to access the edit.php page for a blog /article post id that doesnt exist in the database
$sql = "SELECT * FROM posts WHERE id = '$id'";

// prepare statement for security
if ($stmt = mysqli_prepare($dbcon, $sql)) {

    // execute query
    mysqli_stmt_execute($stmt);

    // store result
    mysqli_stmt_store_result($stmt);
 
	// if a result is not returned from the query, then redirect the user to the index of the portfolio
	if(mysqli_stmt_num_rows($stmt)==0) {
		Redirect('index', false);
		exit();
	} 
}

// Object oriented style prepare statement. Store category information in variables. We don't need a while loop here because we're only storing the information for one database row in to variables.
$stmt = $dbcon->prepare("SELECT id,title,description,post_cat,display_hide FROM posts WHERE id = ?");
// binds variables to a prepared statement as parameters
$stmt->bind_param("i", $id);
// executes a prepared query
$stmt->execute();
// transfers a result set from a prepared statement
$stmt->store_result();
// count the number of rows returned by the query
$numrows = $stmt->num_rows;

// binds variables to a prepared statement for result storage
$stmt->bind_result($id,$title,$description,$post_cat,$display_hide);
/* fetch value */
$stmt->fetch();

// initialize  a variable for displaying success / errors messages to the user
$error_display = '';

// check if the form has been submitted for updating the blog post / article information
if(isset($_POST['upd'])) {
	// if the form has been submitted, then store all of the information entered by the user in variables
	$id = (INT)$_POST['id']; 
	$title = mysqli_real_escape_string($dbcon, $_POST['title']);
	$description = $_POST['description'];
	$category = mysqli_real_escape_string($dbcon, $_POST['category']);
	$display = mysqli_real_escape_string($dbcon, $_POST['display']);
	
	 // object oriented style prepare statement to update database row related to appropriate blog post details
	$stmt = $dbcon->prepare("UPDATE posts SET title=?, description=?, post_cat=?, display_hide=? WHERE id=?");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param('ssiii', $title, $description, $category, $display, $id);
	// executes a prepared query and stores the result as TRUE or FALSE
	$status = $stmt->execute();

	// check if the query executed successfully or not
	// display a success or fail message to the user 
	if ($status === false) {
		$error_display = "<br/>Failed to edit.<br/>";
	} else {
		$error_display = "<br/>Post edited successfully.<br/>";
		echo "<meta http-equiv='refresh' content='2; url=edit?id=$id' />";
	}


}
?>

<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>
<a href="edit_articles"><< return to Blog Post List</a><br/><br/>

<h3>EDIT BLOG POST</h3>

<?php echo $error_display; ?>

<form action="" method="POST" class="w3-container">
<input type="hidden" name="id" value="<?php echo $id;?>">
<label>Title</label>
<input type="text" class="w3-input w3-border" name="title" value="<?php echo $title;?>">

<label>Description</label>
<textarea id = "text_area" class="w3-input w3-border large_textbox" name="description" >
<?php echo $description;?></textarea>

<label>Category</label><br/>
<?php
// pull all of the categories specifically for for blog posts / articles
// create a dropdown menu using a loop
$sql = "SELECT * FROM category";
$result = mysqli_query($dbcon, $sql);

if(mysqli_query($dbcon, $sql)) {
	$select= '<select id="category" name="category">';
	
	while($row=mysqli_fetch_array($result)){
	      $select.='<option name="category" value="'.$row['id'].'">'.$row['catname'].'</option>';
	  }

$select.='</select>';
echo $select;
}

?>

<br/><br/>
<label>Hide Display</label><br/>
<select id="display" name="display" >
	<option id="display" name="display" 
	value="<?php echo $display_hide;?>">
	<?php 
	// The current selection for whether this article / blog post will be hidden from public display or not will come up as an option at the top of the dropdown
	if($display_hide == 1){echo 'YES';}else{echo 'NO';}
	?>
		
	</option>
	<option id="display" name="display" value="1">Yes</option>
	<option id="display" name="display" value="0">No</option>
</select>

<br/><br/>
<input type="submit" class="w3-btn w3-light-grey w3-round" name="upd" value="Submit">
</form>


<?php
// close the connection for greater security
mysqli_close($dbcon);
include("footer.php");
?>