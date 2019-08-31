<?php
/*-------------------------------------------
FILE PURPOSE


/*------------------------------------------*/
?>

<?php
// Select statement that counts the total number of rows that exist in the database table
$sql = "SELECT COUNT(*) FROM category";
// procedural style query on the database using the above select statement
$result = mysqli_query($dbcon, $sql);
//  fetches one row from a result-set and returns it as an enumerated array.
$r = mysqli_fetch_row($result);

// used for paginating the results returned from the query
$numrows = $r[0]; // imagine that this returns a value of 10
$rowsperpage = 15;
$total_pages = ceil($numrows / $rowsperpage); // 10 + 5 = 2 ... if 10 videos are listed in the database, then they will be displayed on 2 pages ... 5 videos per page
$page_url = 'edit_category.php';

if (!isset($_GET['page'])) {$current_page = 1;}
else {$current_page = $_GET['page'];}

$offset = ($current_page - 1) * $rowsperpage;

echo paginate($rowsperpage, $current_page, $numrows, $total_pages, $page_url);

?>

<?php // The following are headers for the top of the table that will display all of the blog category information to the admin. ?>
<table class='w3-table-all'>
<tr class='w3-light-grey w3-hover-light-grey'>
<th class="hide_th_td">ID</th>
<th>Title</th>
<th>Description</th>
<th>Edit</th>
</tr>

<?php
// This php block will store the blog category information returned from the database query in variables and display the table to the user.

// A select statement  to return a result-set of blog category data based on where the administrator is in the pagination navigation
$sql = "SELECT * FROM category ORDER BY id DESC LIMIT $offset, $rowsperpage";
// executes a prepared query and stores the result as a result set or FALSE
$result = mysqli_query($dbcon, $sql);

// if no blog category data is returned from the query, then let the user know
if(mysqli_num_rows($result) < 1) {
	echo "No category found";
} 

// Store the blog category information returned from the database query in variables and .
while ($row = mysqli_fetch_assoc($result)) {
	$id = $row['id'];
	$title = substr($row['catname'], 0, 10).'.'; // sunstr() function truncates the titles of the blog categories if they're longer than 10 characters
	$description = substr($row['description'], 0, 50).'.'; // sunstr() function truncates the description of the blog categories if they're longer than 50 characters

	?>

	<tr>
	<td class="hide_th_td"><?php echo $id;?></td>
	<td><a href="view?id=<?php echo $id;?>"><?php echo $title ;?></a></td>
	<td><?php echo $description;?></td>

	<td class="desktop_dropdown">
		<a href="edit_blogCategory?id=<?php echo $id;?>">Edit</a> |
		<a href="del_blogCategory?id=<?php echo $id;?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
	</td>

	<td class="mobile_dropdown">
		<select id = "mobile_dropdown" onchange="dropdownSelectionCheck(this)">
			<option name="selection" value="#">&#xf013;</option>
		    <option value="edit_blogCategory?id=<?php echo $id;?>">Edit</option>
		    <option value="del_blogCategory?id=<?php echo $id;?>">Delete</option>
		</select>
	</td>
<?php 
} 
echo "</table>";
?>