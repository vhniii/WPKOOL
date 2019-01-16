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


add_action('admin_menu', 'my_admin_menu');

function my_admin_menu () {
  add_management_page('Footer Text', 'Footer Text', 'manage_options', __FILE__, 'footer_text_admin_page');
}

function footer_text_admin_page () {
  echo 'this is where we will edit the variable';
}


 add_management_page( $page_title, $menu_title, $capability, $menu_slug, $function );

?>