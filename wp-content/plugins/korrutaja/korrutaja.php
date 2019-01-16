<?php
/**
* Plugin Name: Korrutaja
* Description: Plugin, mis korrutab kaks arvu omavahel.
* Version: 1.0
* Author: Rasmus Laane
* Author URI: http://rasmuslaane.ikt.khk.ee
**/


function rl_add_menu() {

	add_submenu_page("options-general.php", "Korrutaja | Muutes korrutamisega elu paremaks", "Korrutaja", "manage_options", "$capability", "rl_plugin_page");

}

add_action("admin_menu", "rl_add_menu");

function rl_plugin_page() {

?>

<div><h1>Korrutaja</div>

<div>

<h4 style="font-weight: 700"> Korrutaja oskab korrutada, sisesta 1. ja 2. arv ning veendu ise! </h4><br>
<p>Arv1: <input id="arv1" type="text" oninput="multiply()" style="width: 40px"></p>
<p>Arv2: <input id="arv2" type="text" oninput="multiply()" style="width: 40px"></p>
<p> Vastus on: <input id="result" style="width: 40px"></p>

</div>


<script>

	function multiply() {
		var num1 = document.getElementById('arv1').value;	
		var num2 = document.getElementById('arv2').value;
		var result = document.getElementById('result');	
		var myResult = num1 * num2;
		result.value = myResult;
      
		
	}

</script>

<?php

}


?>