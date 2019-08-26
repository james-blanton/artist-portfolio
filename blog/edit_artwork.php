<?php
/*-------------------------------------------
FILE PURPOSE

This file is found via the admin dashboard. In short, this is used to edit existing artwork. All of the content edited in this file is related to the "artwork" table.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to check that the id gathered from the url is valid. This function can be found in functions.php.
$id = $_GET['id'];
idCheck($id);

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

// object oriented style prepare statement to get the data related to the artwork from the database
$stmt = $dbcon->prepare("SELECT id,title,category,filedate,name,display_hide,index_display FROM artwork WHERE id = ?");
// binds variables to a prepared statement as parameters
$stmt->bind_param("i", $id);
// executes a prepared query
$stmt->execute();
// transfers a result set from a prepared statement
$stmt->store_result();
// get the number of rows returned by the query
$numrows = $stmt->num_rows;

// binds variables to a prepared statement for result storage
$stmt->bind_result($id,$title,$category,$date,$filename,$display,$index);

// fetch values
$stmt->fetch();

// if a result is not returned from the query, then redirect the user to the admin dashboard for the portfolio
if($numrows==0) {
	Redirect('admin', false);
	exit();
} 

// initialize  a variable for displaying success / errors messages to the user
$error_display = '';

// check if the form has been submitted for updating the artwork's information
if(isset($_POST['upd'])) {

	// Update the 'artwork' table using info submitted by the user.
	$id = (INT)$_POST['id'];
	$title = mysqli_real_escape_string($dbcon, $_POST['title']);
	$category = mysqli_real_escape_string($dbcon, $_POST['category']);
	$display = mysqli_real_escape_string($dbcon, $_POST['display']);
	$index = mysqli_real_escape_string($dbcon, $_POST['index']);
	 // object oriented style prepare statement to update database row related to appropriate artwork details
	$stmt = $dbcon->prepare("UPDATE artwork SET title=?, category=?, display_hide=?, index_display=? WHERE id=?");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param('ssiii', $title, $category, $display, $index, $id);
	// executes a prepared query
	$status = $stmt->execute();

	// check if the query executed successfully or not
	// display a success or fail message to the user 
	// refresh the page once the update has been completed so that the values pulled via the SELECT statement at the top of the file can be updated.
	if ($status === false) {
		$error_display = "<br/>Failed to edit artwork.<br/>";
	} else {
		$error_display = "<br/>Artwork edited successfully.<br/>";
		echo "<meta http-equiv='refresh' content='2; url=edit_artwork?id=$id' />";
	}

}
?>

<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>

<a href="edit_paintings"><< return to Drawing / Painting List</a><br/><br/>

<h3>EDIT ARTWORK</h3>

<?php echo $error_display; ?>

<form action="" method="POST" class="w3-container">
<input type="hidden" name="id" value="<?php echo $id;?>">
<label>Title</label>
<input type="text" class="w3-input w3-border" name="title" value="<?php echo $title;?>">

<label>Category</label><br/>
<?php
// pull all of the categories specifically for artwork
// create a dropdown menu using a loop
$sql = "SELECT * FROM category_artwork";
$result = mysqli_query($dbcon, $sql);

if(mysqli_query($dbcon, $sql)) {
	$select= '<select id="category" name="category">';
	$select.='<option id="category" name="category" style="text-transform:uppercase;" value="'.$category.'">'.$category.'</option>';
	while($row=mysqli_fetch_array($result)){
	      $select.='<option name="category" value="'.$row['catname'].'">'.$row['catname'].'</option>';
	  }
}
$select.='</select>';
echo $select;
?>

<br/><br/>
<label>Hide Display</label><br/>
<select id="display" name="display" >
	<option id="display" name="display" 
	value="<?php echo $display;?>">
	<?php 
	// The current selection for whether this artwork will be hidden from public display or not will come up as an option at the top of the dropdown.
	if($display == 1){echo 'YES';}else{echo 'NO';}
	?>
		
	</option>
	<option id="display" name="display" value="1">Yes</option>
	<option id="display" name="display" value="0">No</option>
</select>

<br/><br/>
<label>Index Display</label><br/>
<select id="index" name="index" >
	<option id="index" name="index" 
	value="<?php echo $index;?>">
	<?php 
	// The current selection for whether the artwork is displayed on the index page or not
	if($index == 1){echo 'YES';}else{echo 'NO';}
	?>
		
	</option>
	<option id="index" name="index" value="1">Yes</option>
	<option id="index" name="index" value="0">No</option>
</select>

<br/><br/>
<input type="submit" class="w3-btn w3-light-grey w3-round" name="upd" value="Submit">
</form>

<?php // display the selected artwork to the admin at the bottom of the modification forms ?>
<img src="../artwork/<?php echo $filename; ?>" alt="<?php echo $filename; ?>">


<?php
// close the connection for greater security
mysqli_close($dbcon);
include("footer.php");
?>