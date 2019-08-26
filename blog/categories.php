<?php
/*-------------------------------------------
FILE PURPOSE
All this file does is loop through the 'category' table. This file is included at the end of the index.php file for the blog.
You see this list of categories when you first load up the blog. 
A future release of this portfolio package may need to include pagination for this category list.

Currently, all that this is displaying is the category name and its description while also providing a link to navigate
to a file that will display all of the articles that have been classified under that category name.

Currently we are truncating the description of the categories after 150 characters.

/*------------------------------------------*/
?>

<div class="w3-container w3-center w3-light-grey"><h3>Categories</div>

<?php
// query all rows from the category table
$sql = "SELECT * FROM category";
// executes a prepared query and stores the result as a result set or FALSE
$result = mysqli_query($dbcon, $sql);

if(mysqli_num_rows($result) < 1) {
	// Display an error message if there's no categories listed in the database for some reason.
	echo "<div class='w3-container w3-border-category'>No categories found.</div>";
}

	echo "<div class='w3-container w3-border-category'>";

	while ($row = mysqli_fetch_assoc($result)) {
		$id = $row['id']; // unique id for the category
		$catname = $row['catname']; // name of the category
		$description = $row['description']; // a short description of the category
	?>
	
	<a class="italic" href="cat?id=<?php echo $id;?>"><?php echo $catname;?></a><br>
	<?php 
	// truncate the description of the category down to 150 characters and display it
	echo substr($description, 0, 250).'&#32;...'.'<br/><hr>';
	?>
	
<?php
	}

echo "</div>";
	
?>