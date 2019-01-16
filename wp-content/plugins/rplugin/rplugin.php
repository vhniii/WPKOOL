<?php

/*
Plugin name: RPlugin
Description: Sample Plugin
Version: 1.0
Author: Rasmus Laane
Author URI: rasmuslaane.ikt.khk.ee/wordpress

*/


// include('includes/function.php');

function rl_alert_box(){

echo "<script type='text/javascript'>alert(\"$error\");</script>";

}


add_action( 'init', 'rl_alert_box' );

?>