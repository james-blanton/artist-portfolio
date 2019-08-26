<?php 
/*-------------------------------------------
FILE PURPOSE

This is the index file for the blog / article section of the portfolio.

/*------------------------------------------*/

include("../header.php"); 
// database connection file
include("connect.php");
?>

<div class="w3-panel">
<br>
	<form action="search" method ="GET" class="w3-container">
	<input type ="text" name="q" class="w3-input w3-border" placeholder="Search" required>
		<input type="submit" class="w3-btn w3-light-grey" value="Search">
	</form>
</div>

<?php
// This pho block is for the purposes of creating pagination for the blog posts / articles

// Select statement that counts the total number of rows that exist in the 'posts' table, which contains all blog / article data
$sql = "SELECT COUNT(*) FROM posts WHERE display_hide = 0";
// procedural style query on the database using the above select statement
$result = mysqli_query($dbcon, $sql);
$r = mysqli_fetch_row($result);

// used for paginating the results returned from the query
$numrows = $r[0]; // imagine that this returns a value of 10
$rowsperpage = 5;
$totalpages = ceil($numrows / $rowsperpage); // 10 + 5 = 2 ... if 10 videos are listed in the database, then they will be displayed on 2 pages ... 5 videos per page

// typecast page for security purposes
if(!isset($_GET['page'])){
	$page = 1;
	$_GET['page'] = 1;
}

if(isset($_GET['page']) && is_numeric($_GET['page'])) {
	$page = (INT)$_GET['page'];
} else {
	Redirect('index.php', false);
	exit();
}

// the url would contain a $_GET['page'] value if the user has already navigated back and forth on the pagination navigation links. However, if the page is loaded without clicking on the pagination navigation links, then there will be no page value set in the url, therefor the user needs to be viewing the first page. Setting the $page variable to 1 ensures that this happenss
if(!isset($_GET['page'])){
	$page = 1;
}

// adjust what article / blog post data is being  displayed to the administrator in the html table based on where they are in the pagination navigation
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
	$page = (INT)$_GET['page'];
	} 
	if($page > $totalpages) {
		$page = $totalpages;
		} 
		if($page < 1) {
			$page = 1;
			}
			$offset = ($page - 1) * $rowsperpage;
?>

<?php
// A select statement  to return a result-set of blog post / article data based on where the administrator is in the pagination navigation
$sql = "SELECT * FROM posts  WHERE display_hide = 0 ORDER BY id DESC LIMIT $offset, $rowsperpage";
$result = mysqli_query($dbcon, $sql);

// if no blog post / article data is returned from the query, then let the user know
if(mysqli_num_rows($result) < 1) {
	echo '<div class="w3-panel w3-round">No articles are currently available for public display.</div>';
}

while ($row = mysqli_fetch_assoc($result)) {

	$id = htmlentities($row['id']);
	$title = htmlentities($row['title']);
	$des = htmlentities($row['description']);
	$time = htmlentities($row['date']);

	echo '<div class="w3-panel w3-sand w3-card-4">';
	echo "<h3><a href='view?id=$id'>$title</a></h3><p>";

	// the text for the article is currently being truncated, so users have to click "read more" in order to see the full article
	echo substr($des, 0,500) .'&#32;...';

	echo '</p><div class="w3-text-teal">';
	echo "<a href='view?id=$id'>Read more...</a>";

	echo '</div> <div class="w3-text-grey">';
	echo "$time</div>";
	echo '</div>';
}


echo "<div class='w3-bar w3-center'>";

if($page > 1){
	echo "<a href='?page=1'>&laquo;</a>";
	$prevpage = $page - 1;
	echo "<a href='?page=$prevpage' class='w3-btn'><</a>";
	} 
	$range = 5;
	for ($x = $page - $range;$x < ($page + $range) + 1; $x++) {
		if(($x > 0) &&  ($x <= $totalpages)) {
			if($x == $page) {
				echo "<div class='w3-light-grey w3-button'>$x</div>";
			}

			else {
				echo "<a href='?page=$x' class='w3-button w3-border'>$x</a>";
			} 
		}
}

if($page != $totalpages) {
 	$nextpage  = $page + 1;
	echo "<a href='?page=$nextpage' class='w3-button'>></a>";
	echo "<a href='?page=$totalpages' class='w3-btn'>&raquo;</a>";
} 

echo "</div>";

// display the categories for blog posts / articles at the bottom of the page 
include("categories.php");
include("footer.php");
?>
