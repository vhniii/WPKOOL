<?php


function rl_options_page() {
	ob_start(); ?>
	<div class="wrap">
		<h2>My First WordPress Plugin Options</h2>
		<p>This is our settings page content.</p>
	</div>
	<?php
	echo ob_get_clean();
}


function rl_add_options_link() {
	add_options_page('My First WordPress Plugin Options', 'My First Plugin', 'manage_options', 'rl-options', 'rl_options_page');
}
add_action('admin_menu', 'rl_add_options_link');


?>