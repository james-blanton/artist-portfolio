<?php
/*-------------------------------------------
FILE PURPOSE

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

<h3>PAINTING LIST</h3>
<?php include("tab_paintings.php"); ?>
</div>

<?php
include("footer.php");
?>