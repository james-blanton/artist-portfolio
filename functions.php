<?php
FUNCTION Redirect($url, $permanent = false) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Global redirect function 

	/*------------------------------------------*/

    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

FUNCTION getCurrentDirectory() {
	/*-------------------------------------------
	FUNCTION PURPOSE

	This function returns the name of the current directory location for the file that is being viewed. 
	The function is used to adjust the navigation urls and the paths to the CC files depending upon whether we are in the blog directory or not. 

	/*------------------------------------------*/

    $path = DIRNAME($_SERVER['PHP_SELF']);
    $position = STRRPOS($path,'/') + 1;
    RETURN SUBSTR($path,$position);
}

$current_location = getCurrentDirectory();

FUNCTION idCheck($id) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	This function ensures that an ID entered in the url is truely numeric. It also typecasts the value for greater security.

	/*------------------------------------------*/

    // set the id from the url to 1 if the user tries to view the page with no id being set
    if(!isset($id)){
        $id = 1;
    }

    // Redirect user away if they put some strange id in the url bar
    if(isset($id) && is_numeric($id)) {
        $id = (INT) $id;
    } else {
        Redirect('index', false);
        exit();
    }

    return $id;
}

FUNCTION loginCheck() {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Security check to make sure that admin is logged in. Included at the top of every backend CMS file.

	/*------------------------------------------*/

    // if the user is not logged in, then redirect the user away to the login page before executing any more of this file
    if(!isset($_SESSION['username'])) {
        Redirect('login', false);
        exit();
    } 

}
?>

<?php
FUNCTION display_artwork($type) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	This function is included in the art gallery files (painting.php & drawing.php).
	Each call to the function will display all of the pieces of artwork that have been entered in to the database with a specific category classification. The title of this requested category is passed to the function through the $type paramenter. For example, to call all artworks that have a category of 'painting' you do the following:

	$type = 'painting'; 
	display_artwork($type); 

	-------------------------------------------

	The 'display_hide' field in the datbase is a boolean. If the display_hide row is set to '1' instead of zero for a specific artwork row, then that artwork will not be displayed.

	------------------------------------------*/

	include("blog/connect.php");

	?>

	<div class="flex">

	<?php
	// Select statement to get all of the data on every artwork matching the category name that was passed in to the function as a parameter.
	// Artworks that have their 'display_hide' column set to 1 will not be displayed. 
	$sql= "SELECT id, title, name, filedate, display_hide FROM artwork WHERE category = '$type' AND display_hide = 0 ORDER BY id";
	$result = $dbcon->query($sql);

	// store all data on each artwork in variables that are easier to use
	while($row = $result->fetch_assoc()) {
	$id = $row['id']; // the unique id / primary key for the artwork
	$title = $row['title']; // the title for each artwork
	$filename = $row['name']; // the file name for each artwork including the file extension

	?>

	<div class='column'>
		<div class="img_container">
			<a href="artwork/<?php echo $filename; ?>" target="_new">
				<img class="image" src="artwork/<?php echo $filename; ?>" alt="<?php echo $filename; ?>" width='100%' style='float:left'>

				<div class="middle">
					<div class="img_title"><?php echo $title ?></div><?php //this "middle" div is here to display the title of the artwork when the user drags their mouse over the image. See styles/images.css to alter this ?>
				</div>
			</a>

		</div>
	</div>

	<?php
	}
	?>

	</div>

<?php
}
?>

<?php
FUNCTION display_ownersName() {
	/*-------------------------------------------
	FUNCTION PURPOSE
	
	Query the database for the name of the portfolio / the name of the portfolio owner.
	Used in the header bar at the top of the portfolio and in the footer for the copyright.

	-------------------------------------------*/

     // database connection file
    include("blog/connect.php");
    // displays the portfolio owner's name in the header bar
    // get portfolio owner's first name
    $sql = "SELECT info FROM general_info WHERE field_name = 'owner_firstName'";
    // execute the query
    $result = mysqli_query($dbcon, $sql);

    // store owners first name in a variable
    $row = mysqli_fetch_assoc($result);
    $owner_firstName = $row['info'];

    // get portfolio owner's last name
    $sql = "SELECT info FROM general_info WHERE field_name = 'owner_lastName'";
    // execute the query
    $result = mysqli_query($dbcon, $sql);

    // store owners last name in a variable
    $row = mysqli_fetch_assoc($result);
    $owner_lastName = $row['info'];

	$name = $owner_firstName. ' ' .$owner_lastName;
	return $name;
}
?>

<?php
FUNCTION populate_portfolio_info($data) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	This function is a repeatable query to get general information on the portfolio for use with the edit_contact.php file.
	edit_contact.php can be found in the admin dashboard.

	I'm currently using this function to create a variable for the following:
	(a) The portfolio owners biography blurb.
	(b) The accent color across the top of the portfolio / the color for url mouseovers.
	(c) The portfolio owner's email address.
	(d) The portfolio owner's first name.
	(e) The portfolio owner's last name.
	
	-------------------------------------------*/

	// database connection file
	include("connect.php");

	// get the info for the general info row
	$sql = "SELECT info FROM general_info WHERE field_name = '".$data."'";
	// execute the query
	$result = mysqli_query($dbcon, $sql);

	// store the information in a variable
	$row = mysqli_fetch_assoc($result);
	$data = $row['info'];

	return $data;
}
?>

<?php
FUNCTION update_portfolio_info($data,$data2) {
	/*-------------------------------------------
	FUNCTION PURPOSE
	
	This function is a repeatable query to update general information on the portfolio for use with the edit_contact.php file.
	edit_contact.php can be found in the admin dashboard.
	
	-------------------------------------------*/

	// database connection file
	include("connect.php");

	// query statement to update database row related to appropriate contact / biography details
	$stmt = $dbcon->prepare("UPDATE general_info SET info=? WHERE field_name='".$data."'");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param('s', $data2);
	// executes a prepared query and stores the result as TRUE or FALSE
	$status = $stmt->execute();

	// check if the query executed successfully or not
	// display a success or fail message to the user 
	if ($status === false) {
		$error_display = "<br/>Failed to edit ".$data."<br/>";
	} else {
		$error_display = $data." edited successfully.<br/>";
		$sql = "SELECT * FROM general_info ";

		// prepare statement for security
		if ($stmt = mysqli_prepare($dbcon, $sql)) {

		// executes a prepared query
		mysqli_stmt_execute($stmt);

		// transfers a result set from a prepared statement
		mysqli_stmt_store_result($stmt);
			 

		// if a result is not returned from the query, then redirect the user to edit_contact.php file
		// aka 'Edit 'General Portfolio Info'
			if(mysqli_stmt_num_rows($stmt)==0) {
				Redirect('../edit_contact', false);
				exit();
			} 
		}

	}
}

?>

<?php
FUNCTION populate_user_info($data) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Used to display information related to the portfolio admin via the edit_contact.php file.
	This data is tied to the admin table, but everything else in edit_contact is tied to the 'general_info' table.
	
	-------------------------------------------*/

	// database connection file
	include("connect.php");

	// check logged in user
	$user = $_SESSION['username'];
	// get the info for the general info row
	$sql = "SELECT $data FROM admin WHERE username = '".$user."'";
	// execute the query
	$result = mysqli_query($dbcon, $sql);

	// store the information in a variable
	$row = mysqli_fetch_assoc($result);
	$data = $row[$data];

	return $data;
}
?>

<?php
FUNCTION update_user_info($data,$data2) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Used to update information related to the portfolio admin via the edit_contact.php file.
	This data is tied to the admin table, but everything else in edit_contact is tied to the 'general_info' table.
	
	-------------------------------------------*/

	// database connection file
	include("connect.php");
	// checked logged in user 
	$user = $_SESSION['username'];
	// query statement to update database row related to the administrators information
	$stmt = $dbcon->prepare("UPDATE admin SET $data = ? WHERE username='".$user."'");
	// binds variables to a prepared statement as parameters
	$stmt->bind_param('s', $data2);
	// executes a prepared query and stores the result as TRUE or FALSE
	$status = $stmt->execute();

	// check if the query executed successfully or not
	// display a success or fail message to the user 
	if ($status === false) {
		$error_display = "<br/>Failed to edit ".$data."<br/>";
	} else {
		$error_display = $data." edited successfully.<br/>";
		$sql = "SELECT * FROM general_info ";

		// prepare statement for security
		if ($stmt = mysqli_prepare($dbcon, $sql)) {

		// executes a prepared query
		mysqli_stmt_execute($stmt);

		// transfers a result set from a prepared statement
		mysqli_stmt_store_result($stmt);
			 
		// if a result is not returned from the query, then redirect the user to edit_contact.php file
		// aka 'Edit 'General Portfolio Info'
			if(mysqli_stmt_num_rows($stmt)==0) {
				Redirect('../edit_contact', false);
				exit();
			} 
		}

	}
}

?>

<?php
FUNCTION display_logo() {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Get the file name for the website logo.

	-------------------------------------------*/

	include("blog/connect.php");

	// Select statement to get the file name for the logo
	$sql= "SELECT info FROM general_info WHERE field_name='logo_filename'";
	$result = $dbcon->query($sql);

	// store all data for logo file
	while($row = $result->fetch_assoc()) {
		$filename='artwork/'.$row['info']; // the file name for the logo
		return $filename;
		exit();
	}

}
?>

<?php
FUNCTION paginate($rowsperpage, $current_page, $numrows, $total_pages, $page_url) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Generates pangination links for any database table.
	$rowsperpage is the number of database entries that the admin wishes to have displayed on each page.
	$current_page contained the page that the user is currently only while navigation through the pagination. This is obtained from the url via $_GET.
	$numrows is a count of the total number of rows returned by the database query.
	$total_pages is the total number of pages to be generated, determines by dividing the total number of database rows returned ($numrows) by the total nunber of entries desired for each page ($rowsperpage):
	$numrows / $rowsperpage

	-------------------------------------------*/

	if (!isset($_GET['page'])) {$current_page = 1;}
	else {$current_page = $_GET['page'];}

	$offset = ($current_page - 1) * $rowsperpage;

    $pagination = '';
    if($total_pages > 0 ){ //verify total pages and current page number
    	$pagination .= '<center>';
        $pagination .= '<ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 1; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
        if($current_page > 0){
        	$current_page=1;
        }

        

        if($previous !=0){
        $pagination .= '<li><a href="'.$page_url.'?page='.$previous.'">'.$previous.'</a></li>';
    	}

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="'.$page_url.'?page='.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li><a href="'.$page_url.'?page='.$next_link.'" >></a></li>'; //next link
                $pagination .= '<li class="last"><a href="'.$page_url.'?page='.$total_pages.'" title="Last">»</a></li>'; //last link
        }
        $pagination .= '</center>';
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links
}
?>

<?php
FUNCTION newCategory_include($filename,$type,$category_name) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Generates a php file for a new artwork category. See the file 'new_artCategory.php' for a further explanation on why I'm doing this.

	-------------------------------------------*/

	$data = <<< EOT
<?php
/*-------------------------------------------
FILE PURPOSE

This file displays all artwork listed in the database that has a category of $category_name.

See function.php for the display_artwork() function.
The photo_syles.css file contains CSS specific to $category_name.php

/*------------------------------------------*/
?>
<?php include('header.php'); ?>

<link rel='stylesheet' href='styles/photo_styles.css'>

<div id='home' class='tab-pane fade in active' class='float_fullHeight'>
<?php $type = '$category_name'; display_artwork($type); ?>
</div>
			      
</div>
</div>

<?php include('footer.php'); ?>

EOT;

return $data;
}
?>

<?php
FUNCTION generate_resetEmail($email_from, $content) {
	/*-------------------------------------------
	FUNCTION PURPOSE

	Generates a php file for a new artwork category. See the file 'new_artCategory.php' for a further explanation on why I'm doing this.

	-------------------------------------------*/

	$data = <<< EOT
	    <html>
		<head>
		<style type="text/css">
		body, html {
			background: #F1F1F1;
			width:100%;
			height:100%;
		}

		h1 {
			font-family: "futura-pt", "Segoe UI", "Helvetica Neue", Arial, sans-serif !important;
			text-transform: uppercase !important;
			text-decoration: none !important;
			letter-spacing: 0px !important;
			font-weight: 300 !important;
			font-size: 35px !important;
		}

		h2 {
			font-family: "futura-pt", "Segoe UI", "Helvetica Neue", Arial, sans-serif !important;;
			margin-top: 10px;
			margin-bottom: 10px;
			font-weight: 300 !important;;
			font-size: 25px !important;
			text-transform: uppercase;
		}

		.container_outerContent {
			padding-bottom: 2rem;
			color: #040404;
			background-color: #F1F1F1;
			height:100%;
			padding:3%;
		}

		.container_innerContent {
			width:80%;
			bottom: 0;
			position: relative;
			background-color: #FFFFFF;
			z-index: 100;
			margin-left: auto;
			margin-right: auto;
			padding: 15px;
			padding-top: 100px;
			box-shadow: 12px 0 15px -4px rgba(154, 154, 154, 0.8), -12px 0 8px -4px #9E9E9E;
		}

		.small, small {
			font-size:60% !important;
		}

		</style>

		</head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

		<body>

			<div class="container_outerContent">
				<div class="container_innerContent" style="padding:20px;">
					<p>$content</p>
					<h2><small><?php echo $email_from; ?></small></h2>
				</div>
			</div>

		</body>
		</html>

EOT;

return $data;
}
?>