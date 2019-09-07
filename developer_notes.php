<?php
echo <<< EOT
This portfolio was originally created for personal use only.
I later decided that I wished to make the code publically distributable and
relatively usable for someone who had very little experience with writing code for the web.

I am a novice programmer, so this portfolio package is not without flaws.

Here are some currentlty known issues in the code:

(a) 'del.php' contains an error display message that the user will never see if an error does infact occur. This file is used to delete blog posts through the admin dashboard.

(b) 'del_artwork.php' contains an error display message that the user will never see if an error does infact occur. This file is used to delete artworks through the admin dashboard.

(c) artwork  categories need to have a character limit for their name

(d) the amount of artwork categories that can be displayed in the header navigation needs to be limited

EOT;

?>