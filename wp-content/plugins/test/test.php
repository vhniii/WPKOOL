<?php
/**
* Plugin Name: Test
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/



add_action('wp_head', 'my_func');


function my_func() {

	echo 'Hello World';

}