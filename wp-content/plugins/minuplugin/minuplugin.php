<?php
/**
* Plugin Name: Test
* Description: Plugin, mis log-ib konsooli etteantud teksti.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/

add_action("wp_loaded", "console_logging");


function console_logging() {

?>

	<script>
		var by = " RL Plugin ";
		var msg = "Console Log By:";
		console.log(msg);
	</script>

<?php
}



function rl_add_menu() {

	add_submenu_page("options-general.php", "RL Plugin", "RL Plugin", "manage_options", "rl_plugin", "rl_plugin_page");

}

add_action("admin_menu", "rl_add_menu");

function rl_plugin_page() {

?>

<div class=""><h1> RL Plugin By: <a href="rasmuslaane.ikt.khk.ee/wordpress">Rasmus</a></h1></div>

<?php

}


?>