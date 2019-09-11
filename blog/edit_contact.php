<?php
/*-------------------------------------------
FILE PURPOSE

For editing biography / contact page info.

/*------------------------------------------*/

include("../header.php");
// database connection file
include("connect.php");

// Function to ensure that the user is logged in as an admin. This function can be found in functions.php.
loginCheck();

// Query the database for general portfolio information and store the information in variables. This function can be found in functions.php.
$data = 'biography';
$biography = populate_portfolio_info($data);

$data = 'color_accent';
$accent_color = populate_portfolio_info($data);

$data = 'owner_email';
$owner_email = populate_portfolio_info($data);

$data = 'owner_firstName';
$owner_firstName = populate_portfolio_info($data);

$data = 'owner_lastName';
$owner_lastName = populate_portfolio_info($data);

$data = 'security_question';
$security_question = populate_user_info($data);

$data = 'security_answer';
$security_answer = populate_user_info($data);

// initialize  a variable for displaying success / errors messages to the user
$error_display = '';

if(isset($_POST['upd'])) {
	// if the form has been submitted, then store all of the information entered by the user in to variables
	$biography = $_POST['biography'];
	$icon = $_POST['icon'];
	$accent_color = '#'.$_POST['accent_color'];
	$owner_email = $_POST['owner_email'];
	$owner_firstName = $_POST['owner_firstName'];
	$owner_lastName = $_POST['owner_lastName'];
	$security_question = $_POST['security_question'];
	$security_answer = $_POST['security_answer'];
		
	$data = 'biography';
	$data2 = $biography;
	update_portfolio_info($data,$data2);

	$data = 'color_accent';
	$data2 = $accent_color;
	update_portfolio_info($data,$data2);

	$data = 'logo_filename';
	$data2 = $icon;
	update_portfolio_info($data,$data2);

	$data = 'owner_email';
	$data2 = $owner_email;
	update_portfolio_info($data,$data2);

	$data = 'owner_firstName';
	$data2 = $owner_firstName;
	update_portfolio_info($data,$data2);

	$data = 'owner_lastName';
	$data2 = $owner_lastName;
	update_portfolio_info($data,$data2);

	$data = 'security_question';
	$data2 = $security_question;
	update_user_info($data,$data2);

	$data = 'security_answer';
	$data2 = $security_answer;
	update_user_info($data,$data2);

}

?>

<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>
<a href="../contact" > << return to About Page</a><br/><br/>

<h3>EDIT GENERAL WEBSITE INFO</h3>

<?php echo $error_display; ?>

<form action="" method="POST" class="w3-container">

<label>Biography (5000 characters)</label>
<textarea class="w3-input w3-border large_textbox" name="biography" maxlength="5000"><?php echo $biography;?></textarea>

<label>Portfolio Header Icon</label><br/>
Select an image to use as the portfolio header icon.<br/>
Artwork is listed in this dropdown by title.<br/>
The icon will change once the page has been refreshed.<br/><br/>

<?php
// pull the titles of all artwork listed in the database
// create a dropdown menu for these titles using a loop
$sql = "SELECT title, name FROM artwork";
$result = mysqli_query($dbcon, $sql);

if(mysqli_query($dbcon, $sql)) {
	$select= '<select id="icon" name="icon">';
	while($row=mysqli_fetch_array($result)){
		$select.='<option name="icon" value="'.$row['name'].'">'.$row['title'].'</option>';
	}
}
$select.='</select>';
echo $select;
?>

<br/>
<br/>

<label>Accent Color</label><br/>
Select color for top bar of portfolio, url underline and image hover box shadow.<br/><br/>
<input class="jscolor" value="<?php echo $accent_color; ?>" name="accent_color">
<br/><br/>

<label>Admin Contact Email</label><br/>
<input type="email" value="<?php echo $owner_email; ?>" name="owner_email">
<br/><br/>

<label>Admin First Name</label><br/>
<input type="text" value="<?php echo $owner_firstName; ?>" name="owner_firstName">
<br/><br/>

<label>Admin Last Name</label><br/>
<input type="text" value="<?php echo $owner_lastName; ?>" name="owner_lastName">
<br/><br/>

<label>Password Security Question</label><br/>
<select id="security_question" name="security_question" class="full-width-dropdown">
<option name="security_question" value="<?php echo $security_question; ?>" class="dropdown-uppercase"><?php echo $security_question; ?></option>
<option name="security_question" value="What was the name of your first pet?">What was the name of your first pet?</option>
<option name="security_question" value="What is your mother's middle name?">What is your mother's middle name?</option>
<option name="security_question" value="What is your mother's birthday?">What is your mother's birthday?</option>
<option name="security_question" value="What was the color of your first car?">What was the color of your first car?</option>
<option name="security_question" value="What's the name of the street you grew up on?">What's the name of the street you grew up on?</option>
<option name="security_question" value="What high school did you attend?">What high school did you attend?</option>
</select>
<br/><br/>

<label>Security Question Answer</label><br/>
<input type="text" value="<?php echo $security_answer; ?>" name="security_answer">
<br/><br/>

<br/><br/>

<input type="submit" class="w3-btn w3-light-grey w3-round" name="upd" value="Submit">
</form>


<?php
// close the connection for great security
mysqli_close($dbcon);
include("footer.php");
?>