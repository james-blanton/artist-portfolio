<?php
/*-------------------------------------------
FILE PURPOSE

This header file includes the primary navigation to drawings, paintings, about and blog sections of the portfolio.
The following if else statement is used to adjust the urls and the paths for the css depending upon whether you are in the root directory or the blog directory:

    if ($current_location == "blog")
    {
        // if we're in the blog directory, then adjust the url here to go back to a file that's 1 directory level back
    }
    else {
        // if we're not in the blog directory, then adjust the url here to go to a file that's within the same directory
    }

This IF block is also used in the footer file.

/*------------------------------------------*/

// custom pho functions
include('functions.php');
// general-purpose required configuration information
include('config.php');

session_start();
?>

<!DOCTYPE HTML>
<html>
<?php echo "<title>".display_ownersName()."</title>"; ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- external google file for portfolio title font found in the header -->
<link href="https://fonts.googleapis.com/css?family=Bilbo+Swash+Caps&display=swap" rel="stylesheet">
<?php


// this ico file is for the browser icon
if ($current_location == "blog")
{
echo 
'
<link rel="shortcut icon" type="images/x-icon" href="../images/ico.ico" />
';
}
else {
echo
'
<link rel="shortcut icon" type="images/x-icon" href="images/ico.ico" />
';
}
?>

<?php // general purpose font and boot strap styling + jquery ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css">
    
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />


<?php
// custom CSS style files
if ($current_location == "blog")
{
echo 
'
<link rel="stylesheet" type="text/css" href="../styles/main_style.css">
<link rel="stylesheet" type="text/css" href="../styles/style.php">
';
}
else {
echo
'
<link rel="stylesheet" type="text/css" href="styles/main_style.css">
<link rel="stylesheet" type="text/css" href="styles/style.php">
';
}
?>

<?php
// custom javascript files
if ($current_location == "blog")
{
echo 
'
<script src="../javascript_functions.js"></script>
<script src="../jscolor.js"></script>
';
}
else {
echo
'
<script src="javascript_functions.js"></script>
<script src="jscolor.js"></script>
';
}
?>

<?php // all meta data ?>
<meta name="description" content="<?php display_ownersName()?>" />
<meta name="keywords" content="<?php display_ownersName()?>">
<meta name="author" content="<?php display_ownersName()?>">
<meta name="viewport" content="width=device-width" />

<body>

<div class="header_bar">
	<div id="links">
        
        <nav="nav">
            <nav class="nav">
                <div class="logo">

                    <?php 
                    // don't display the logo circle if a logo image hasn't been selected by the admin
                    if(display_logo()!='artwork/'){ 
                    ?>

                    <span class="logo_circle">
                        <center>
                            <?php
                            // calls to a function found in functions.php in order to display the portfolio logo / icon in the  header next to the portfolio title
                            // the image used for this icon can be set by the portfolio admin in the administrative backend control panel
                            
                            if ($current_location == "blog"){$logo ='../'.display_logo();} else {$logo=display_logo();}
                                if ($current_location == "blog")
                                {
                                    echo '<a href="../index"><img style="float:left"; src="'.$logo.'"></a>';
                                }
                                else {
                                    echo '<a href="index"><img style="float:left"; src="'.$logo.'"></a>';
                                }
                            
                            ?>
                        </center>
                    </span>
                    <?php } ?>

                	<h1>
					<?php
                    // print url with portfolio owners name beside portfolio logo / icon
				    if ($current_location == "blog")
				    {
				        echo '<a href="../index">'.display_ownersName().'</a>';
				    }
				    else {
				        echo '<a href="index">'.display_ownersName().'</a>';
				    }
				    ?>
					</h1>
                </div> 
                
                <?php // start display for navigation urls at desktop resolution ?>
                <ul>
                    <?php
                    /*
                    Get the name and filename of the artwork categories from the database.
                    */

                    if ($current_location == "blog")
                    {
                        include("connect.php");
                    }
                    else {
                        include("blog/connect.php");
                    }

                    $sql = "SELECT catname,filename,header_display FROM category_artwork WHERE header_display = 1";
                    $result = mysqli_query($dbcon, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $title = $row['catname'];
                        $filename = $row['filename'];
                        echo '<li>';
                        if ($current_location == "blog")
                        {
                            echo '<a href="../'.$filename.'">'.$title.'</a>';
                        }
                        else {
                            echo '<a href="'.$filename.'">'.$title.'</a>';
                        }
                        echo '</li>';
                    }
                    ?>

                    <li>

                    <?php
                    if ($current_location == "blog")
                    {
                        echo '<a href="../contact">About</a>';
                    }
                    else {
                        echo '<a href="contact">About</a>';
                    }
                    ?>
                    </li>

                    <li>
                    <?php
                    if ($current_location == "blog")
                    {
                        echo '<a href="index">Blog</a>';
                    }
                    else {
                        echo '<a href="blog/index">Blog</a>';
                    }
                    ?>
                    </li>                 
                </ul>
			</nav>
        </nav>
    </div>
</div>

<?php // start display for alternate header bar that fits a mobile resolution ?>
<div class="mobile_nav">
	<div class="mobile_logo">
		<h1>
            <?php
            // print url with portfolio owners name
            if ($current_location == "blog")
            {
                echo '<a href="../index">'.display_ownersName().'</a>';
            }
            else {
                echo '<a href="index">'.display_ownersName().'</a>';
            }
            ?>
		</h1>
	</div>



    <?php
    // database connection file
    if ($current_location == "blog")
    {
        include("connect.php");
    }
    else 
    {
        include("blog/connect.php");
    }
    
    /*
    Get the name and filename of the artwork categories from the database.
    */

    $sql = "SELECT catname,filename,header_display FROM category_artwork WHERE header_display = 1";
    $result = mysqli_query($dbcon, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['catname'];
        $filename = $row['filename'];
        echo '<div class="mobile_nav_bar">';
        if ($current_location == "blog")
        {
            echo '<a href="../'.$filename.'">'.$title.'</a>';
        }
        else 
        {
            echo '<a href="'.$filename.'">'.$title.'</a>';
        }
         echo '</div>';
    }
    ?>

    <div class="mobile_nav_bar">
    <?php
    if ($current_location == "blog")
    {
        echo '<a href="../contact">About</a>';
    }
    else {
        echo '<a href="contact">About</a>';
    }
    ?>
    </div>

    <div class="mobile_nav_bar">
    <?php
    if ($current_location == "blog")
    {
        echo '<a href="index">Blog</a>';
    }
    else {
        echo '<a href="blog/index">Blog</a>';
    }
    ?>
    </div>
    
</div>

</div>

<div class="container_outerContent">

    <div class="container_innerContent">
