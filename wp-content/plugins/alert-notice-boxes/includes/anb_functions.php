<?php
function anb_set_limitations($alert_notice_id,$anb_post_id,$anb_enabled) {
	$anb_limitations = get_post_meta( $anb_post_id, "limitations_anb_option", true );
	$anb_limitations_times = get_post_meta( $anb_post_id, "times_custom_limitations_anb_option", true );
	$anb_limitations_days = get_post_meta( $anb_post_id, "days_custom_limitations_anb_option", true );
	$anb_cookie_limitation_name = 'limitation_anb_' . $alert_notice_id;
	$click_on_close_button = get_post_meta( $anb_post_id, "click_on_close_button_anb_option", true );

	if ( $anb_enabled == 'enabled' && get_post_status ( $anb_post_id ) == 'publish' && $click_on_close_button != 'cancel-for') {
		setcookie("close_anb_" . $alert_notice_id, "", time() - 3600);
	}

	if ( $anb_limitations == 'custom-limitations' ) {
		if(!isset($_COOKIE[$anb_cookie_limitation_name])) {
			setcookie("limitation_anb_" . $alert_notice_id, "1", time() + (86400 * $anb_limitations_days));
		} else {
			$anb_cookie_limitation_count = $_COOKIE[$anb_cookie_limitation_name] + 1;
			setcookie("limitation_anb_" . $alert_notice_id, $anb_cookie_limitation_count, time() + (86400 * $anb_limitations_days));
		}
	} else {
		if ( $anb_enabled == 'enabled' && get_post_status ( $anb_post_id ) == 'publish' ) {
			setcookie("limitation_anb_" . $alert_notice_id, "", time() - 3600);
		}
	}
}

function is_showing_post($anb_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$anb = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %s", $anb_id) );
	$alert_notice_display_in = $anb->display_in;
	$get_page_id = get_the_ID();
	$disable_all_alerts_page = 'disable_all_alerts_page_' . $get_page_id;
	$get_disable_all_alerts_page = get_post_meta($get_page_id, $disable_all_alerts_page, true);
	$alert_display = 'display_alert_' . $anb_id . '_page_' . $get_page_id;
	$get_alert_display = get_post_meta($get_page_id, $alert_display, true);
	$return = false;

	$page_post_type_name = get_post_type($get_page_id);
	$check_post_type = strpos($alert_notice_display_in, $page_post_type_name);
	if ($check_post_type !== false && !$get_disable_all_alerts_page) {
		$return = true;
	} elseif ($get_alert_display == $anb_id) {
		$return = true;
	}

	if (class_exists('BuddyPress') && !bp_is_blog_page()) {
		$return = false;
		if (bp_is_current_component('front')) {
			$check_bp_page = strpos($alert_notice_display_in, 'front');
			$return = ($check_bp_page !== false) ? true : false;
		} elseif (bp_is_current_component('activity')) {
			$check_bp_page = strpos($alert_notice_display_in, 'activity');
			$return = ($check_bp_page !== false) ? true : false;
		} elseif (bp_is_current_component('members')) {
			$check_bp_page = strpos($alert_notice_display_in, 'members');
			$return = ($check_bp_page !== false) ? true : false;
		} elseif (bp_is_current_component('profile')) {
			$check_bp_page = strpos($alert_notice_display_in, 'profile');
			$return = ($check_bp_page !== false) ? true : false;
		}
	}
	return $return;
}

function anb_show($anb_id,$page_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$anb = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %s", $anb_id) );

	$anb_post_id = $anb->post_ID;
	$alert_title = $anb->title;
	$anb_post = get_post($anb_post_id);
	$anb_enabled = $anb->enabled;
	$anb_user_types = $anb->user_types;
	$anb_content = (isset($anb_post->post_content)) ? do_shortcode($anb_post->post_content) : null;
	$anb_design = $anb->design_id;
	$anb_device = $anb->device_class;
	$anb_animation = $anb->animation_id;
	$alert_notice_delay = $anb->delay;
	$alert_notice_display_in = $anb->display_in;
	$alert_notice_show_time = $anb->show_time;
	$anb_show = true;

	if ($anb_user_types == 'only-logged-users' && !is_user_logged_in()) {
		$anb_show = false;
	}

	if ($anb_user_types == 'only-guests' && is_user_logged_in()) {
		$anb_show = false;
	}

	if (get_post_status($anb_post_id) != 'publish') {
		$anb_show = false;
	}

	if ($anb_enabled != 'enabled') {
		$anb_show = false;
	}
	return $anb_show;
}

function create_anb_css_stylesheet() {
	$anb_css_file = fopen( YCANB_PLUGIN_DIR . "css/anb-dynamic.css", "w") or die("Unable to open file!");
	$css_code = '';
	foreach (glob( YCANB_PLUGIN_DIR . "css/parts/*.css") as $css) {
		$file = basename($css);
		$css_code .= file_get_contents( YCANB_PLUGIN_DIR . 'css/parts/' . $file );
		$css_code .= "\n";
	}
	fwrite($anb_css_file, $css_code);
	fclose($anb_css_file);
}

function add_to_ycgps_js($js_url) {
	$print_js = "<script type='text/javascript' src='";
	$print_js .= $js_url;
	$print_js .= "' defer></script>";
	echo $print_js;
}

function add_to_ycgps_css($css_url) {
	$print_css = "<link rel='preload' href='";
	$print_css .= $css_url;
	$print_css .= "' type='text/css' media='all' />";
	echo $print_css;
}

function fix_old_ver() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$anbs = $wpdb->get_results( "SELECT * FROM $table_name");
	foreach ($anbs as $anb) {
		$anb_id = $anb->id;
		$anb_delay = $anb->delay;
		$anb_delay_update = $anb_delay / 1000;
		$anb_show_time = $anb->show_time;
		$anb_show_time_update = $anb_show_time / 1000;
		$wpdb->update ( $table_name, array(
		        'delay' => $anb_delay_update,
				'show_time' => $anb_show_time_update

		), array('id' => $anb_id));
	}
}
