<?php
/*-------------------------------------------
FILE PURPOSE

This file modifies a portfolio theme color in the CSS based on admin input in the control panel.

/*------------------------------------------*/

// database connection file
include("../blog/connect.php");

// select statement to acquire all details related to the users biography and other general information
$sql= "SELECT info FROM general_info WHERE field_name = 'color_accent'";
$result = $dbcon->query($sql);

// let the user know if there is no biography information available for display
$num_rows = mysqli_num_rows($result);
if($num_rows == 0){
	$barColor='#00000';
}

while($row = $result->fetch_assoc()) {
	$barColor= $row['info'];
}

//Set the content-type header and charset.
header("Content-Type: text/css; charset=utf-8");

?>

/** CSS begins **/
.header_bar {
    border-top: 5px solid <?php echo $barColor; ?>;
}

a:hover {
    color: #000000;
    text-decoration: underline <?php echo $barColor; ?>;
    text-decoration-skip: ink;
}

.logo_circle:hover {
    border: 3px solid <?php echo $barColor; ?>;
}

.column:hover {
  box-shadow: 0 0 8px <?php echo $barColor; ?>;
}

.mobile_nav {
	border-top: 5px solid <?php echo $barColor; ?>;
}