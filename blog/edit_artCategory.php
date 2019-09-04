<?php
/*-----------------------------
FILE PURPOSE

The purpose of this file to allow the admin to edit existing categories for artwork.

/*----------------------------*/

include("../header.php");
// database connection
include("connect.php");

// get the category id from the url
$id = $_GET['id'];

// query all information from the database for the selected art category
$sql = "SELECT id FROM category_artwork WHERE id = '$id'";

// executes a prepared query for security purposes
// redirects the user if they attempt to select a art category that does not exist
if($stmt = mysqli_prepare($dbcon,$sql)) {
    // execute query
    mysqli_stmt_execute($stmt);
    // store result
    mysqli_stmt_store_result($stmt);
    
    // if a result is not returned from the query, then redirect the user to the index of the portfolio
    if(mysqli_stmt_num_rows($stmt)==0) {
        Redirect('index', false);
        exit();
    }  
}

// object oriented query to store all art category information in to variables
$stmt = $dbcon->prepare("SELECT id,catname,description,filename FROM category_artwork WHERE id = ?");
// bind variables to a prepared statement as parameters
$stmt->bind_param("i", $id);
// executes prepared query
$stmt->execute();
// transfers a result set from a prepared statement
$stmt->store_result();
// binds variables to a prepared statement for result storage
$stmt->bind_result($id,$category_title,$category_description,$filename);
// fetch data
$stmt->fetch();

// initialize a variable for error display
$error_display = '';

// check if the form has been submitted for updating the category information
if(isset($_POST['upd'])) {
    // if the form has been submitted, then store all information needed in to new variables
    $id = (INT)$_GET['id'];
    $catname = mysqli_real_escape_string($dbcon,$_POST['category_name']);
    $description = mysqli_real_escape_string($dbcon,$_POST['description']);
    $new_filename = mysqli_real_escape_string($dbcon,$_POST['filename']);
    $header_display = (INT)$_POST['header_display'];

    // object oriented style prepared statement to update database row related to appropriate art category
    $stmt = $dbcon->prepare("UPDATE category_artwork SET catname=?, description=?, filename=?, header_display=? WHERE id=?");
    // binds variables to a prepared statement as parameters
    $stmt->bind_param('sssii', $catname, $description,$new_filename,$header_display,$id);
    // executes a prepared query and stores the result as TRUE or FALSE
    $status = $stmt->execute();

    /*
    If the portfolio owner edits the name of the gallery, then the file name 
    for the gallery will get changed as well, which will change the header urls.
    */

    // get the directory that the art gallery file is located in
    $directory = $_SERVER['DOCUMENT_ROOT'].'/'.$portfolio_directory.'/';
    
    // set variables for the current name of the file and for the renamed file
    $new_filename=$new_filename.'.php';
    $filename=$filename.'.php';
    // rename the file for the art directory 
    rename($directory.$filename,$directory.$new_filename);

    // check if the query executed successfully or not
    // display a success or fail message to the user 
    if ($status === false) {
        $error_display = "<br/>Failed to edit.<br/>";
    } else {
        $error_display = "<br/>Category edited successfully.<br/>";
        echo "<meta http-equiv='refresh' content='2; url='edit_category_manage'/>";
    }
}

?>

<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>
<a href="art_category_manage"><< return to Art Category Managment</a><br/><br/>

<h3>EDIT ART CATEGORY</h3>

<?php echo $error_display; ?>

<form action="" method="POST" class="w3-container">
<input type="hidden" name="id" value="<?php echo $id;?>">
<label>Title</label>
<input type="text" class="w3-input w3-border" name="category_name" value="<?php echo $category_title;?>">

<label>Description</label>
<textarea id = "text_area" class="w3-input w3-border large_textbox" name="description" >
<?php echo $category_description;?></textarea>

<label>Filename</label> (Don't include the extension)
<input type="text" class="w3-input w3-border" name="filename" value="<?php echo $filename;?>">

<label>Header Display</label><br/>
<select name="header_display">
        <option value="1">yes</option>
        <option value="0">no</option>
</select>

<br/><br/>
<input type="submit" class="w3-btn w3-light-grey w3-round" name="upd" value="Submit">
</form>


<?php
// close the connection for greater security
mysqli_close($dbcon);
include("footer.php");
?>