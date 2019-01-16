<?php

/**
* Plugin Name: Test plugin
* Plugin URI: http://www.mainwp.com
* Description: This plugin does some stuff with WordPress
* Version: 1.0.0
* Author: Your Name Here
* Author URI: http://www.mainwp.com
* License: GPL2
*/

?>


add_action('wp_loaded', 'my_func');

function my_func() {

	echo 'hello world';

}