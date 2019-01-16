<?php
/**
* Plugin Name: Test
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/


add_filter('wp_footer', 'my_function');

function my_function() {

	echo 'Hello';

}


?>