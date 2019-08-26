<?php
/*-------------------------------------------
FILE PURPOSE

This footer file contains only the copyright line and a link for logging in as an administrator.
See header.php for an explanation of why I am using if/else blocks to display the urls.

I am able to use the if / else block for the urls in the footer because the function that 
determines the current directory is located in the footer, which is attached at the top of every page.

The function display_ownersName() can be found in functions.php.
The portfolio owner provides their first and last name in the admin panel. 

/*------------------------------------------*/

?>

</div> <!-- end container_innerContent -->

</div> <!-- end container_outerContent -->

<div class="copyright">
<ul class="menu">

<li>Portfolio content Copyright © <?php echo date("Y"); ?> <?php echo display_ownersName(); ?>. All rights reserved. Portfolio design by <a href="http://james-blanton.com" target="_blank">James Blanton</a>.</li>

<?php 
/* 
Only display the admin panel url when the user has already logged in to an account
currently the only user in the database is the portfolio owner, so we don't need to do something like
creating permission levels so that this url is only displayed to those who are admins.

For the time being, if a user has an account set up in the database, then they are able
to access this administration control panel. 
*/

if(isset($_SESSION['username']) && isset($_SESSION['username']) != '') { 
	if ($current_location == "blog")
	{
		echo '<li><a href="admin"><i class="fas fa-user"></i></a></li>
		';
	}
	else {
		echo '<li><a href="blog/admin"><i class="fas fa-user"></i></a></li>
		';
	}
}
else {
	if ($current_location == "blog")
	{
		echo '<li><a href="login"><i class="fas fa-sign-in-alt"></i></a></li>
		';
	}
	else {
		echo '<li><a href="blog/login"><i class="fas fa-sign-in-alt"></i></a></li>
		';
	}
}

?>
</ul>

</div>

</body>
</html>
