<?php
/*-------------------------------------------
FILE PURPOSE

This file is used to configure the portfolio when the user first sets it up on their server host.

/*------------------------------------------*/

/*
Are your portfolio files contained within the root directory of your hosting service, or are they 
in a nested folder?

If they are contained in the root directory, then set this variable to true.
If they are contained within a nested folder, then set this variable to  false.

This is neccessary in order for the portfolio owner to be able to create new categories by 
utilizing the file new_artCategory.php, which is accessed via the admin control panel.

Please see the new_artCategory file for a further explanation.
*/
$root_directory = false;


// Set the name of the directory that the portfolio is contained within if it is not located in the root directory.
$portfolio_directory = 'artist-portfolio';

?>