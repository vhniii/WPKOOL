<?php
/**
* Plugin Name: Test
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/


add_action( 'wp_footer', 'my_thank_you_text' );

function my_thank_you_text () {
    echo '<p>Thank you for reading!</p>';

}


?>