<?php
/*-------------------------------------------
FILE PURPOSE

Contains a list of all artwork categories along with an edit and delete option for each.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php
loginCheck();
    
?>
<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>

<?php
$error_display = "";
// error display for deleting blog posts / articles
if(isset($_GET['e'])){
	if($_GET['e']=='deleted'){
		$error_display = "Art category deleted successfully.";
	} elseif($_GET['e']=='error') {
		$error_display = "Error deleting entry. Database entry more than likely no longer exists.";
	}
} else { $error_display == ""; }

echo $error_display;
?>


<h3>ART CATEGORY MANAGMENT</h3>
<?php include("tab_artCategory.php"); ?>
</div>

<?php
include("footer.php");
?>