<?php 
/*-------------------------------------------
FILE PURPOSE

This file is for searching information in the blog / article post table. Currently it will only search the titles of blog posts.

The user is meant to click on the article title or "read more" in order to be taken to the view.php, which will display the full blog / article post.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");
?>

<a href="index"> << return to Blog Index</a><br/>

<?php
// Retrieve Search Term
if (isset($_GET['q'])) {
    $search = "%".$_GET['q']."%";
}

// object oriented style prepare statement for database search
if ($stmt = $dbcon->prepare("SELECT id, title, description, date FROM posts WHERE title LIKE ?")) {

	// binds variables to a prepared statement as parameters
    $stmt->bind_param('s', $search);

	// executes a prepared query
    $stmt->execute();

	// binds variables to a prepared statement for result storage
	$stmt->bind_result($id, $title, $des, $time);

    // execute the Statement
    $stmt->execute();

    // transfers a result set from a prepared statement
    mysqli_stmt_store_result($stmt);

    printf("<br/>Number of results: %d.\n", mysqli_stmt_num_rows($stmt));
	
	while ($stmt->fetch()) {

			// display results of search
	        echo '<div class="w3-panel w3-sand w3-card-4">';
			echo "<h3><a href='view?id=$id'>$title</a></h3><p>";

			echo substr($des, 0,100); // truncates the display of the blog / article post at 100 characters

			echo '</p><div class="w3-text-teal">';
			echo "<a href='view?id=$id'>Read more...</a>";

			echo '</div> <div class="w3-text-grey">';
			echo "$time</div>";
			echo '</div>';
		}
    
}
include("footer.php"); 
?>
