<?php
/**
* Plugin Name: Test
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/


function rl_add_menu() {

	add_submenu_page("options-general.php", "RL Plugin", "RL Plugin", "manage_options", "rl_plugin", "rl_add_menu");

}

add_action("admin_menu", "rl_add_menu");


?>