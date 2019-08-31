<?php
/*-----------------------------
FILE PURPOSE
The purpose of this file to allow the admin to edit existing categories for blog entries / articles. 

/*----------------------------*/

include("../header.php");
// database connection
include("connect.php");

// get the category id from the url
$id = $_GET['id'];

// query all information from the database for the selected blog category
$sql = "SELECT id FROM category WHERE id = '$id'";

// executes a prepared query for security purposes
// redirects the user if they attempt to select a blog category that does not exist
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

// object oriented query to store all category information in to variables
$stmt = $dbcon->prepare("SELECT id,catname,description FROM category WHERE id = ?");
// bind variables to a prepared statement as parameters
$stmt->bind_param("i", $id);
// executes prepared query
$stmt->execute();
// transfers a result set from a prepared statement
$stmt->store_result();
// binds variables to a prepared statement for result storage
$stmt->bind_result($id,$category_title,$category_description);
// fetch data
$stmt->fetch();

// initialize a variable for error display
$error_display = '';

// check if the form has been submitted for updating the category information
if(isset($_POST['upd'])) {
    // if the form has been submitted, then store all information needed  in to new variables
    $id = (INT)$_POST['id'];
    $catname = mysqli_real_escape_string($dbcon,$_POST['category_name']);
    $description = mysqli_real_escape_string($dbcon,$_POST['description']);

    // object oriented style prepared statement to update database row related to appropriate blog category
    $stmt = $dbcon->prepare("UPDATE category SET catname=?, description=? WHERE id=?");
    // binds variables to a prepared statement as parameters
    $stmt->bind_param('ssi', $catname, $description,$id);
    // executes a prepared query and stores the result as TRUE or FALSE
    $status = $stmt->execute();

    // check if the query executed successfully or not
    // display a success or fail message to the user 
    if ($status === false) {
        $error_display = "<br/>Failed to edit.<br/>";
    } else {
        $error_display = "<br/>Category edited successfully.<br/>";
        echo "<meta http-equiv='refresh' content='2; url=edit_blogCategory' />";
    }
}

?>

<div class="w3-container w3-light-grey"><h2>Admin Dashboard</h2></div>

<div class="w3-container">

<a href="admin"><< return to Admin Dashboard</a><br/><br/>
<a href="blog_category_manage"><< return to Blog Category Managment</a><br/><br/>

<h3>EDIT BLOG CATEGORY</h3>

<?php echo $error_display; ?>

<form action="" method="POST" class="w3-container">
<input type="hidden" name="id" value="<?php echo $id;?>">
<label>Title</label>
<input type="text" class="w3-input w3-border" name="category_name" value="<?php echo $category_title;?>">

<label>Description</label>
<textarea id = "text_area" class="w3-input w3-border large_textbox" name="description" >
<?php echo $category_description;?></textarea>

<br/><br/>
<input type="submit" class="w3-btn w3-light-grey w3-round" name="upd" value="Submit">
</form>


<?php
// close the connection for greater security
mysqli_close($dbcon);
include("footer.php");
?>