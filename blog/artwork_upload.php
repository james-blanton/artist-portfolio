<?php
/*-------------------------------------------
FILE PURPOSE

This file uploads a new artwork file in to the 'artwork' directory and creates an entry in the 'artwork' database table.

The file consists of the html forms that send data to artwork_read.php for processing.

The fields displayed include:
- title 
- category

Currently, this form will attempt to process a file of any size. However, the php.ini file should prevent any file larger than 1GB from being uploaded to the hosting platform. The javascript at the bottom of the file will warn the use if they have selected a file for upload that is larger than 4.60 MB (4,827,360 bytes)

/*------------------------------------------*/

include("../header.php");

// database connection file
Include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php
loginCheck();
		
?>

<?php
// Processes any artwork files that are uploaded 
include('artwork_read.php'); 
?>

<div class="w3-container w3-light-grey"><h2>Artwork Upload</h2></div>
<div class="w3-container">

<a href="admin"> << return to Admin Dashboard</a><br/>

<div id="user_messages">
<?php 
// Error messages are set in artwork_read.php
// avascript will alert the user to whether the file is too large or not in this div area as soon as they select the file with the file input portion of the form
echo $user_messages;
?>
</div>

<br/>
<form action="" method='post' enctype="multipart/form-data">

Max file size is exactly 4.60 MB (4,827,360 bytes) <br/><br/>
<label class="custom-file-upload">
    <input id="artwork_input" type="file" name="file" />
    <i class="fa fa-upload"></i> Upload
</label>
<br><br>

<label>Title</label><br/>
<input type="text" class="w3-input w3-border" name="title" >

<label>Category</label><br/>
<?php
// Pull all of the categories available for artwork.
// Create a dropdown menu using a loop.
$sql = "SELECT * FROM category_artwork";
$result = mysqli_query($dbcon, $sql);

if(mysqli_query($dbcon, $sql)) {
	$select= '<select id="category" name="category">';
	while($row=mysqli_fetch_array($result)){
	      $select.='<option name="category" value="'.$row['catname'].'">'.$row['catname'].'</option>';
	  }
}
$select.='</select>';
echo $select;
?>

<br><br>

<?php
// sets whether the image will be hidden from public display or not
?>
<label>Display Hidden</label><br/>
<select id="display" name="display" >
	<option id="display" name="display" value="1">Yes</option>
	<option id="display" name="display" value="0">No</option>
</select>
<br><br>
	
<input type="submit"  class="w3-btn w3-light-grey w3-round" name="submit" value="Submit"/>

<script type="text/javascript">
// This will warn the use if the file they're attempting to upload is above ~1 MB
var uploadField = document.getElementById("artwork_input");

// 4.60 MB file limit size (4,827,360 bytes)
uploadField.onchange = function() {
    if(this.files[0].size > 4827360){
		document.getElementById("user_messages").innerHTML = "<br/>File too big!";
		this.value = "";
    }else{
		document.getElementById("user_messages").innerHTML = "<br/>File size is acceptable!";
    };
};
</script>

</form>
</div>
</div>
<?php
	
include("footer.php");
?>