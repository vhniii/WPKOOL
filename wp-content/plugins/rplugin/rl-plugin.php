<?php

/*
Plugin name: RPlugin
Description: Sample Plugin
Version: 1.0
Author: Rasmus Laane
Author URI: rasmuslaane.ikt.khk.ee/wordpress

*/

add_action( 'wp_footer', 'my_function' );

function my_function() {
  echo 'hello world';
}

?>