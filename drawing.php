<?php 
/*-------------------------------------------
FILE PURPOSE

This file displays all artwork listed in the database that has a category of 'drawing'.
See function.php for the display_artwork() function.
The photo_syles.css file contains CSS specific to painting.php and drawing.php.

/*------------------------------------------*/

include('header.php'); 

?>

<link rel="stylesheet" href="styles/photo_styles.css">

<div id="home" class="tab-pane fade in active" class="float_fullHeight">
  <?php $type = 'drawing'; display_artwork($type); ?>
</div>
      
</div>
</div>

<?php include('footer.php'); ?>