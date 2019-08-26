<?php
/*-------------------------------------------
FILE PURPOSE

This file is displayed after a user clicks on a category classification found on the index page of the blog / article section of the portfolio.
The page will display a preview of every blog post that belongs to the category: 
the title, description, author, view count, and date for each  article with the assigned category.

Finally, the page will display an edit and delete link if an admin is logged in to their account while viewing this page.
No pagination has been implemented for this section of the blog yet. 

/*------------------------------------------*/

// Connection to the database
include("connect.php");
include("../header.php");

echo '<a href="index"> << return to Blog Index</a><br/>';

// Function to check that the id gathered from the url is valid. This function can be found in functions.php.
$id = $_GET['id'];
idCheck($id);

/*
If the user attempts to view a category id that does not exist in the database, 
then redirect them away from this file. This select query will also be used to display the category name at the top of page.
*/
$sql = "SELECT * FROM category WHERE id = '$id'";
// Executes a prepared query and stores the result as TRUE or FALSE.
$result = mysqli_query($dbcon, $sql);
// f no row is returned, then redirect the user.
if(mysqli_num_rows($result) == 0){
	Redirect('index', false);
	exit();
} 

// Display the category name within a gray div area that stretched the full width its container div.
while ($row = mysqli_fetch_assoc($result)) {
	$post_cat = $row['id'];
	$catname = $row['catname'];
	$description = $row['description'];
	echo '<div class="w3-container w3-center w3-light-grey">';
	echo "<h3>".$catname."</h3></div>";
	echo $description."<hr>";
}
	
// Find all of blog posts / articles that have the same category ID as the current category that the user is viewing.
$sql1 = "SELECT * FROM posts WHERE post_cat = '$post_cat' ORDER BY id DESC";
// Executes a query and stores the result as TRUE or FALSE.
$res = mysqli_query($dbcon, $sql1); 

// If a result is not returned for the query, then  display a message notifying the user that the category is empty.
if(mysqli_num_rows($res) == 0) {
	echo "No article or blog posts found related to this specific category.";
} else {

	// Display any exisiting  articles / blog posts that belong to this category.
	while($r = mysqli_fetch_assoc($res)) {
		// The following variables store data related to each blog post.
		$id = $r['id']; // Unique id for the blog post / article.
		$title = $r['title']; // Title for the blog post / article.
		$description = $r['description']; // The full article / blog post text. This will be truncated on line 67.
		$time = $r['date']; // The date that the article / blog post was submitted in to the database on.

		echo '<div class="w3-panel w3-sand w3-card-4">';
		echo "<h3><a href='view?id=$id'>$title</a></h3><p>";

		// Truncate the text for the article if it's longer than 100 characters. 
		// Display the first 100 characters to the user.
		if(strlen($description) > 100) {
			echo substr($description, 0, 100)."...";
		} else {
			echo $description;
		}


		echo '</p><div class="w3-text-light-grey">';
		echo "<a href='view?id=$id'>Read more</a>";

		echo '</div> <div class="w3-text-grey">';
		echo "$time</div>";
		echo '</div>';
	}
}

include("footer.php");
?>