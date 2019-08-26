<?php
/*-------------------------------------------
FILE PURPOSE
Files 'tab_articles', 'tab_drawings', and 'tab_paintings' could be condensed and improved somehow, but I haven't figured out a appropriate approach yet.

This file is included within the Admin Dashboard file (admin.php).
The file's purpose is to display a table which lists all blog posts that have been submitted to the database.
This data currently includes the unique ID (primary key), the title, the category and 2 urls: edit, and delete.

The titles of the blog posts are truncated if they exceed 15 characters in total and an ellipse (...) is added to the end of the truncated title.

Once the browser reaches 1071px the content modification links (edit, delete ect) are collapsed in to a dropdown menu. 
I'm using javascript to redirect the user when these dropdown menu options are clicked through the use of window.location.href=url.value;
See the dropdownSelectionCheck() function found in root/javascript_functions.js if you wish to modify this function.

I hid the gear font-awesome icon with this simple css line due to the fact that font-awesome won't display correctly in dropdown options while on mobile:
#mobile_dropdown > option:nth-child(1){
    display: none;
}
/*------------------------------------------*/
?>

<?php
// Select statement that counts the total number of rows that exist in the database table
$sql = "SELECT COUNT(*) FROM posts";
// procedural style query on the database using the above select statement
$result = mysqli_query($dbcon, $sql);
//  fetches one row from a result-set and returns it as an enumerated array.
$r = mysqli_fetch_row($result);

// used for paginating the results returned from the query
$numrows = $r[0]; // imagine that this returns a value of 10
$rowsperpage = 15;
$total_pages = ceil($numrows / $rowsperpage); // 10 + 5 = 2 ... if 10 videos are listed in the database, then they will be displayed on 2 pages ... 5 videos per page
$page_url = 'edit_articles.php';

if (!isset($_GET['page'])) {$current_page = 1;}
else {$current_page = $_GET['page'];}

$offset = ($current_page - 1) * $rowsperpage;

echo paginate($rowsperpage, $current_page, $numrows, $total_pages, $page_url);

?>

<?php // The following are headers for the top of the table that will display all of the blog post information to the admin. ?>
<table class='w3-table-all'>
<tr class='w3-light-grey w3-hover-light-grey'>
<th class="hide_th_td">ID</th>
<th>Title</th>
<th class="hide_th_td">Date</th>
<th>Views</th>
<th>Action</th>
</tr>

<?php
// This php block will store the article information returned from the database query in variables and display the table to the user.

// A select statement  to return a result-set of blog post / article data based on where the administrator is in the pagination navigation
$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $rowsperpage";
// executes a prepared query and stores the result as a result set or FALSE
$result = mysqli_query($dbcon, $sql);

// if no blog post / article data is returned from the query, then let the user know
if(mysqli_num_rows($result) < 1) {
	echo "No post found";
} 

// Store the blog post information returned from the database query in variables and .
while ($row = mysqli_fetch_assoc($result)) {
	$id = $row['id'];
	$title = substr($row['title'], 0, 10).'.'; // sunstr() function truncates the titles of the blog posts if they're longer than 15 characters
    $by = $row['posted_by'];
    $time = $row['date'];
    $time = date("m/d/y", strtotime($row['date'])); // formats the display of the datae as month / day / year
    $hits = $row['hits']; // the number of times that the article has been viewed

	?>

	<tr>
	<td class="hide_th_td"><?php echo $id;?></td>
	<td><a href="view?id=<?php echo $id;?>"><?php echo $title ;?></a></td>
	<td><?php echo $time;?></td>
	<td><?php echo $hits;?></td>

	<td class="desktop_dropdown">
		<a href="edit?id=<?php echo $id;?>">Edit</a> |
		<a href="del?id=<?php echo $id;?>" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
	</td>

	<td class="mobile_dropdown">
		<select id = "mobile_dropdown" onchange="dropdownSelectionCheck(this)">
			<option name="selection" value="#">&#xf013;</option>
		    <option value="edit_artwork?id=<?php echo $id;?>">Edit</option>
		    <option value="del_artwork?id=<?php echo $id;?>">Delete</option>
		</select>
	</td>
<?php 
} 
echo "</table>";
?>