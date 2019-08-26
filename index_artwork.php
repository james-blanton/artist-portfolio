<?php
/*-------------------------------------------
FILE PURPOSE

This file is for displaying artwork on the index page of the portfolio that have a database 'index_display' row value of 1 (true).

/*------------------------------------------*/

// database connection file
include("blog/connect.php");

// query to get all neccessary information on  videos that have had their index display set to 1 (TRUE)
$sql= "SELECT name FROM artwork WHERE index_display = 1";
$result = $dbcon->query($sql);

while($row = $result->fetch_assoc()) {
	$filename= $row['name'];
	echo '<img src="artwork/'.$filename.'" alt="'.$filename.'">';
}  

?> 
