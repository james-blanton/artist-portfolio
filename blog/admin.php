<?php
/*-------------------------------------------
FILE PURPOSE

This file is the backend administrator control panel.
This page can currently be accessed by anyone who has an account setup in the database table 'admin'.

The control panel currently provides links to do the following:
- Create blog posts / articles
- Upload artwork

Below these links there's 3 tabs that will display information and modification options for these 2 content types just mentioned; articles / blog posts and artwork (paintings & drawings).

The code each tab of content is contained within a seperate php file in order to make my code more organized and easier to manage. 

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php
loginCheck();
		
?>
<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<p>Welcome <?php echo $_SESSION['username']; ?></p>
<a href="logout">Logout</a>
<hr class="dotted">
<h3>Admin Tools</h3>

<div class="w3-container">
<ul class="admin">
	<li><a href="edit_contact">Edit 'General Portfolio Info'</a></li>
</ul>
</div>
<hr>

<b>Blog</b><br/>
<div class="w3-container">
<ul class="admin">
	<li><a href="new">Create Blog Post</a></li>
	<li><a href="edit_articles">Edit Blog Posts</a></li>
	<li><a href="create_blogCategory">Create Blog Category</a></li>
	<li><a href="blog_category_manage">Edit Blog Categories</a></li>
</ul>
</div>

<b>Artwork</b><br/>
<div class="w3-container">
<ul class="admin">
	<li><a href="artwork_upload">Artwork Upload</a></li>
	<li><a href="edit_paintings">Edit Paintings</a></li>
	<li><a href="edit_drawings">Edit Drawings</a></li>
	<li><a href="new_artCategory">Create Art Category</a></li>
	<li><a href="art_category_manage">Edit Art Categories</a></li>
</ul>
</div>

</div>

<?php
include("footer.php");
?>