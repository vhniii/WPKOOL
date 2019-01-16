<?php
/**
 * Plugin Name: Alert Notice Boxes
 * Plugin URI: http://www.madadim.co.il
 * Description: Create Alert Notice Box wherever you want
 * Version: 2.1.4
 * Author: Yehi Co
 * Author URI: http://www.madadim.co.il
 * License: GPL2
 * Text Domain: alert-notice-boxes


Alert Notice Boxes is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Alert Notice Boxes is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Alert Notice Boxes. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

define( 'YCANB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'YCANB_PLUGIN_URL', plugin_basename(__FILE__) );

include( YCANB_PLUGIN_DIR . 'includes/anb_db.php' );
include( YCANB_PLUGIN_DIR . 'includes/anb_cpt.php' );
include( YCANB_PLUGIN_DIR . 'includes/anb_item_meta_box.php' );
include( YCANB_PLUGIN_DIR . 'includes/anb_functions.php' );
include( YCANB_PLUGIN_DIR . 'classes/class_animations.php' );
include( YCANB_PLUGIN_DIR . 'classes/class_animations_out.php' );
include( YCANB_PLUGIN_DIR . 'classes/class_designs.php' );
include( YCANB_PLUGIN_DIR . 'classes/class_locations.php' );

// foreach ( glob( YCANB_PLUGIN_DIR . "extensions/*.php" ) as $file ) {
// 	include_once $file;
// }

/*-------------------class start------------------*/

class YCanb {

function __construct() {
	add_action( 'admin_menu', array($this, 'anb_admin_menu' ) );
	add_action( 'plugins_loaded', array($this, 'anb_load_textdomain' ) );
	add_action( 'save_post', array($this, 'save_post_type_values' ) );
	add_action( 'before_delete_post', array($this, 'delete_post_row' ) );
	add_action( 'wp_footer', array( $this, 'register_alert_notice_boxes' ) );
	add_action( 'wp_enqueue_scripts', array( $this, 'add_anb_scripts' ), 99 );
	add_action( 'wp_enqueue_scripts', array( $this, 'add_anb_styles' ), 99 );
	add_action( 'manage_pages_custom_column' , array( $this, 'anb_custom_columns' ), 10, 2 );
	add_filter( 'manage_anb_posts_columns' , array( $this, 'anb_columns' ) );
	add_action( 'add_meta_boxes', array( $this, 'add_individual_control_meta_box' ) );
	add_action( 'save_post', array( $this, 'save_individual_control_meta_box' ), 10, 3);
	add_action( 'admin_enqueue_scripts', array($this, 'add_admin_style') );
	add_action( 'admin_action_anb_duplicate_post_as_draft', array($this, 'duplicate_anb_as_draft') );
	add_action( 'ycgps_add_defer_js', array($this, 'add_ycgps_scripts' ) );
	add_action( 'ycgps_add_preload_css', array($this, 'add_ycgps_styles' ) );
	// filter
	add_filter( 'post_updated_messages', array($this, 'anb_update_messages' ) );
	add_filter( 'hidden_meta_boxes', array( $this, 'anb_remove_post_meta_boxes' ), 10, 3 );
	add_filter( 'page_row_actions', array( $this, 'duplicate_anb_link' ), 10, 2 );
	// register
}

function add_admin_style() {
    $screen = get_current_screen();
	if (isset($screen->id)) {
	    if ( $screen->id == 'anb' || $screen->id == 'anb_animations' || $screen->id == 'anb_animations_out' || $screen->id == 'anb_designs' || $screen->id == 'anb_locations' ) {
			$anb_css_ver = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . 'css/admin_anb.css' ));
			wp_register_style( 'anb-style', plugins_url( 'css/admin_anb.css', YCANB_PLUGIN_URL ), false, $anb_css_ver );
			wp_enqueue_style( 'anb-style' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			wp_enqueue_script( 'wp-color-picker' );
			$anb_js_ver  = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . 'js/admin-anb.js' ));
			wp_register_script( 'anb-js', plugins_url( 'js/admin-anb.js', YCANB_PLUGIN_URL ), array(), $anb_js_ver );
			wp_enqueue_script( 'anb-js' );

	        wp_dequeue_script( 'autosave' );
	    }
    }
}

function save_post_type_values() {
    if( get_post_type() == 'anb' ) {
		global $wpdb;
		$post = get_post();
		$table_name = $wpdb->prefix . 'alert_notice_boxes'; // do not forget about tables prefix
		$alert_notice_box = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_ID = %d", $post->ID) );
		$post_id = (isset($alert_notice_box->post_ID)) ? $alert_notice_box->post_ID : null;
		$post_types = get_post_types( array( 'public' => true ) );
		if (class_exists('BuddyPress')) {
			$boddypress_typs = array(
				'front' => 'BuddyPress user main page',
				'activity' => 'BuddyPress activitys',
				'members' => 'BuddyPress members',
				'profile' => 'BuddyPress profile',
			);
			$post_types = array_merge($post_types, $boddypress_typs);
		}
		$alert_notice_box_post_types = '';

		foreach ( $post_types as $post_type => $post_type_name ) {
			$post_type_name_atr = (isset($_POST[$post_type])) ? sanitize_text_field( $_POST[$post_type] ) : null;
			if ($post_type_name_atr != null) {
				$check_post_type = strpos($post_type_name_atr, $post_type);
				if ($check_post_type !== false) {
					$alert_notice_box_post_types .= sanitize_text_field( $_POST[$post_type] . ' ,  ' );
				}
			}
		}
		$prevent_delete_meta_movetotrash = (isset($_POST['prevent_delete_meta_movetotrash'])) ? $_POST['prevent_delete_meta_movetotrash'] : null;
		if (!wp_verify_nonce($prevent_delete_meta_movetotrash, YCANB_PLUGIN_URL.$post->ID)) { return $post_id; }

		if ( $post_id == $post->ID ) {
			$alert_notice_box_id = $alert_notice_box->id;
			$alert_notice_box_title = sanitize_text_field( $_POST['post_title'] );
			$alert_notice_box_display_in = $alert_notice_box_post_types;
			$alert_notice_box_location = sanitize_text_field( $_POST['location'] );
			$alert_notice_box_design = sanitize_text_field( $_POST['design'] );
			$alert_notice_box_animation = sanitize_text_field( $_POST['animation'] );
			$alert_notice_box_animation_out = sanitize_text_field( $_POST['animation_out'] );
			$alert_notice_box_delay = sanitize_text_field( $_POST['delay'] );
			$alert_notice_box_show_time = sanitize_text_field( $_POST['show_time'] );
			$alert_notice_box_enabled = sanitize_text_field( $_POST['enabled'] );
			$alert_notice_box_user_types = sanitize_text_field( $_POST['user_types'] );
			$alert_notice_box_device_class = sanitize_text_field( $_POST['devices'] );
			$wpdb->update ( $table_name, array(
			        'title' => $alert_notice_box_title,
			        'display_in' => $alert_notice_box_display_in,
					'location_id' => $alert_notice_box_location,
			        'design_id' => $alert_notice_box_design,
			        'animation_id' => $alert_notice_box_animation,
			        'animation_out_id' => $alert_notice_box_animation_out,
					'delay' => $alert_notice_box_delay,
					'show_time' => $alert_notice_box_show_time,
			        'enabled' => $alert_notice_box_enabled,
			        'user_types' => $alert_notice_box_user_types,
					'device_class' => $alert_notice_box_device_class

			), array('id' => $alert_notice_box_id));

		} else {

			$alert_notice_box_title = sanitize_text_field( $_POST['post_title'] );
			$alert_notice_box_display_in = $alert_notice_box_post_types;
			$alert_notice_box_location = sanitize_text_field( $_POST['location'] );
			$alert_notice_box_design = sanitize_text_field( $_POST['design'] );
			$alert_notice_box_animation = sanitize_text_field( $_POST['animation'] );
			$alert_notice_box_animation_out = sanitize_text_field( $_POST['animation_out'] );
			$alert_notice_box_delay = sanitize_text_field( $_POST['delay'] );
			$alert_notice_box_show_time = sanitize_text_field( $_POST['show_time'] );
			$alert_notice_box_enabled = sanitize_text_field( $_POST['enabled'] );
			$alert_notice_box_user_types = sanitize_text_field( $_POST['user_types'] );
			$alert_notice_box_device_class = sanitize_text_field( $_POST['devices'] );
			$wpdb->insert ( $table_name, array(
			        'post_ID' => $post->ID,
			        'title' => $post->post_title,
			        'display_in' => $alert_notice_box_display_in,
					'location_id' => $alert_notice_box_location,
			        'design_id' => $alert_notice_box_design,
			        'animation_id' => $alert_notice_box_animation,
					'animation_out_id' => $alert_notice_box_animation_out,
					'delay' => $alert_notice_box_delay,
					'show_time' => $alert_notice_box_show_time,
			        'enabled' => $alert_notice_box_enabled,
			        'user_types' => $alert_notice_box_user_types,
					'device_class' => $alert_notice_box_device_class
			));
		}

		$anb_post_limitations_anb_option = $_POST["limitations_anb_option"];
		update_post_meta($post_id, "limitations_anb_option", $anb_post_limitations_anb_option);
		$anb_post_times_custom_limitations_anb_option = $_POST["times_custom_limitations_anb_option"];
		update_post_meta($post_id, "times_custom_limitations_anb_option", $anb_post_times_custom_limitations_anb_option);
		$anb_post_days_custom_limitations_anb_option = $_POST["days_custom_limitations_anb_option"];
		update_post_meta($post_id, "days_custom_limitations_anb_option", $anb_post_days_custom_limitations_anb_option);
		$anb_post_click_on_close_button_anb_option = $_POST["click_on_close_button_anb_option"];
		update_post_meta($post_id, "click_on_close_button_anb_option", $anb_post_click_on_close_button_anb_option);
		$anb_post_days_click_on_close_button_anb_option = $_POST["days_click_on_close_button_anb_option"];
		update_post_meta($post_id, "days_click_on_close_button_anb_option", $anb_post_days_click_on_close_button_anb_option);

		create_anb_css_stylesheet();
	}
}

function delete_post_row($delete_row){

    if( get_post_type() == 'anb' ) {

	global $wpdb;
	$post = get_post();
	$table_name = $wpdb->prefix . 'alert_notice_boxes';

	$delete_row = "DELETE FROM $table_name WHERE post_ID= $post->ID";
	$wpdb->query($delete_row);
    }
}

public function anb_admin_menu() {
    // add_menu_page( __( 'Alert Notice', 'alert-notice-boxes' ), __( 'Alert Notice', 'alert-notice-boxes' ), 'edit_anbs', 'edit.php?post_type=anb', '', 'dashicons-welcome-add-page' );
    do_action ( 'anb_admin_menu' );
	// add_submenu_page( 'edit.php?post_type=anb', __( 'Add ons', 'alert-notice-boxes' ), __( 'Add ons', 'alert-notice-boxes' ), 'edit_anbs', 'edit.php?post_type=anb_add_ons' );
}

function anb_load_textdomain() {
    load_plugin_textdomain( 'alert-notice-boxes', false, plugin_basename( YCANB_PLUGIN_DIR . 'languages' ) );
}

function anb_columns($columns) {
	unset(
		$columns['date'],
		$columns['comments']
	);
	$new_columns = array(
		'enabled' => __('Enabled', 'alert-notice-boxes'),
		'display_in' => __('Published in', 'alert-notice-boxes'),
		'devices' => __('Devices', 'alert-notice-boxes'),
		'location' => __('Location', 'alert-notice-boxes'),
		'delay' => __('Delay', 'alert-notice-boxes'),
		'show_time' => __('Show Time', 'alert-notice-boxes'),
	);
    return array_merge($columns, $new_columns);
}

function anb_custom_columns( $column, $post_id ) {
    global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$alert_notice_box = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_ID = %d", $post_id) );
    switch ( $column ) {

    case 'enabled' :
	$alert_notice_enabled = $alert_notice_box->enabled;

        echo $alert_notice_enabled;
        break;

    case 'display_in' :
	$alert_notice_display_in = $alert_notice_box->display_in;

        echo $alert_notice_display_in;
        break;

    case 'devices' :
	$alert_notice_devices = $alert_notice_box->device_class;

        echo $alert_notice_devices;
        break;

    case 'location' :
	$alert_notice_location = $alert_notice_box->location_id;
	if ($alert_notice_location == '' || $alert_notice_location == null) {
		$alert_notice_location_print = '';
	} else {
		$alert_notice_location_print = get_the_title($alert_notice_location);
	}

        echo $alert_notice_location_print;
        break;

	case 'delay' :
	$alert_notice_delay = $alert_notice_box->delay;

        echo $alert_notice_delay;
        break;

	case 'show_time' :
	$alert_notice_show_time = $alert_notice_box->show_time;

        echo $alert_notice_show_time;
        break;
	}
}

function add_anb_scripts() {
	if (!class_exists('YCgps')) {
	    $anb_js_ver  = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/js/anb.js' ));
	    wp_register_script( 'anb-js', plugins_url( '/js/anb.js', YCANB_PLUGIN_URL ), array(), $anb_js_ver );
	    wp_enqueue_script( 'anb-js' );
	}
}

function add_ycgps_scripts() {
	if (class_exists('YCgps')) {
		$anb_js_ver  = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/js/anb.js' ));
		$anb_js = plugins_url( '/js/anb.js', YCANB_PLUGIN_URL ) . '?ver=' . $anb_js_ver.PHP_EOL;
		add_to_ycgps_js($anb_js);
	}
}

function add_anb_styles() {
	if (!class_exists('YCgps')) {
		$anb_css_ver = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/css/anb.css' ));
	    wp_register_style( 'anb-style', plugins_url( '/css/anb.css', YCANB_PLUGIN_URL ), false, $anb_css_ver );
	    wp_enqueue_style( 'anb-style' );

		$anb_css_ver = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/css/anb-dynamic.css' ));
	    wp_register_style( 'anb-dynamic-style', plugins_url( '/css/anb-dynamic.css', YCANB_PLUGIN_URL ), false, $anb_css_ver );
	    wp_enqueue_style( 'anb-dynamic-style' );
	}
}

function add_ycgps_styles() {
	if (class_exists('YCgps')) {
		$anb_css_ver = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/css/anb.css' ));
		$anb_css = plugins_url( '/css/anb.css', YCANB_PLUGIN_URL ) . '?ver=' . $anb_css_ver.PHP_EOL;
		add_to_ycgps_css($anb_css);

		$anb_css_ver = date("ymd-Gis", filemtime( YCANB_PLUGIN_DIR . '/css/anb-dynamic.css' ));
		$anb_css = plugins_url( '/css/anb-dynamic.css', YCANB_PLUGIN_URL ) . '?ver=' . $anb_css_ver.PHP_EOL;
		add_to_ycgps_css($anb_css);
	}
}

function anb_update_messages( $messages ) {

		global $post, $post_ID;

		$messages['anb' ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Alert Notice Box updated.', 'alert-notice-boxes' ),
			2 => __( 'Alert Notice Box updated.', 'alert-notice-boxes' ),
			3 => __( 'Alert Notice Box deleted.', 'alert-notice-boxes' ),
			4 => __( 'Alert Notice Box updated.', 'alert-notice-boxes' ),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __( 'Alert Notice Box restored to revision from %s', 'alert-notice-boxes' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Alert Notice Box published.', 'alert-notice-boxes' ),
			7 => __( 'Alert Notice Box saved.', 'alert-notice-boxes' ),
			8 => __( 'Alert Notice Box submitted.', 'alert-notice-boxes' ),
			9 => __( 'Alert Notice Box scheduled for.', 'alert-notice-boxes' ),
			10 => __( 'Alert Notice Box draft updated.', 'alert-notice-boxes' ),
		);

		return $messages;

}

function register_alert_notice_boxes() {
	global $wpdb;
	$post = get_post();
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$anbs = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY delay DESC");
	$get_page_id = get_the_ID();
	$anb_locations = get_posts(array(
		'posts_per_page'=> -1,
		'post_type' => 'anb_locations',
	));
	$print_page = '';
	$print_default_location = '<div id="anb-default-location">' . PHP_EOL;
	$anb_default_location_count = 0;
	foreach ($anbs as $alert_notice_values) {
		if ( $alert_notice_values->location_id == null || $alert_notice_values->location_id == 'default' ) {
			$alert_notice_id = $alert_notice_values->id;
			$anb_post_id = $alert_notice_values->post_ID;
			$anb_post = get_post($anb_post_id);
			$anb_enabled = $alert_notice_values->enabled;
			$anb_content = (isset($anb_post->post_content)) ? do_shortcode($anb_post->post_content) : null;
			$anb_design = $alert_notice_values->design_id;
			$anb_device = $alert_notice_values->device_class;
			$anb_animation = $alert_notice_values->animation_id;
			$anb_animation_out_id = $alert_notice_values->animation_out_id;
			$anb_delay = $alert_notice_values->delay;
			$anb_show_time = $alert_notice_values->show_time;
			$alert_notice_display_in = $alert_notice_values->display_in;

			$anb_close_button = get_post_meta( $anb_post_id, "click_on_close_button_anb_option", true );
			$anb_close_button_days = get_post_meta( $anb_post_id, "days_click_on_close_button_anb_option", true );
			$anb_limitations = get_post_meta( $anb_post_id, "limitations_anb_option", true );
			$anb_limitations_times = get_post_meta( $anb_post_id, "times_custom_limitations_anb_option", true );
			$anb_limitations_days = get_post_meta( $anb_post_id, "days_custom_limitations_anb_option", true );
			$anb_animation_out_post_option_animation_speed = get_post_meta( $anb_animation_out_id, "anb_animation_out_post_option_animation_speed", true );
			if ($anb_animation_out_post_option_animation_speed == '' || $anb_animation_out_post_option_animation_speed == null || $anb_animation_out_post_option_animation_speed < 0) {
				$anb_animation_out_post_option_animation_speed = 0;
			}

			if ( $anb_close_button == 'cancel-for' ) {
				$anb_close_button_val = $anb_close_button_days;
			} else {
				$anb_close_button_val = 0;
			}

			if ( $anb_limitations == 'custom-limitations' ) {
				$anb_limitations_times_val = $anb_limitations_times;
				$anb_limitations_days_val = $anb_limitations_days;
			} else {
				$anb_limitations_times_val = 0;
				$anb_limitations_days_val = 0;
			}

			if( anb_show($alert_notice_id,$get_page_id) && is_showing_post($alert_notice_id) ) {
				if ($anb_design == null) {
					$anb_design_class = 'anb anb-class-success';
					$anb_close_design = 'close-anb-success';
				}else {
					$anb_design_class = 'anb anb-class-' . $anb_design;
					$anb_close_design = 'close-anb-' . $anb_design;
				}
				if ($anb_animation == null || $anb_animation == 'default') {
					$anb_animation = 'anb-animation-default';
				}else {
					$anb_animation = 'anb-animation-id-' . $anb_animation;
				}
				$print_default_location .= '<div class="anb-bg">'.PHP_EOL;
				$print_default_location .= '	<div id="anb-id-' . $alert_notice_id . '" class="' . $anb_design_class . ' ' . $anb_device . ' ' . $anb_animation . ' delay" data-anb-id="' . $alert_notice_id . '" data-anb-delay="' . $anb_delay . '" data-anb-show-time="' . $anb_show_time . '" data-anb-limitations-times="' . $anb_limitations_times_val . '" data-anb-limitations-days="' . $anb_limitations_days_val . '" data-anb-animation-out-class="anb-animation-out-id-' . $anb_animation_out_id . '" data-anb-animation-out-speed="' . $anb_animation_out_post_option_animation_speed . '">'.PHP_EOL;
				if ( get_post_meta( $anb_design, "anb_close_button_position_option_disable", true ) != 'yes' ) {
					$print_default_location .= '	<span id="close-anb-id-' . $alert_notice_id . '" class="' . $anb_close_design . '" tabIndex="0" title="close box button" data-anb-close-button="' . $anb_close_button_val . '">&#x2715;</span>'.PHP_EOL;
				}
				$print_default_location .= wpautop($anb_content);
				$print_default_location .= '	</div>'.PHP_EOL;
				$print_default_location .= '</div>';
				$anb_default_location_count++;
			}
		}
	}
	$print_default_location .= '</div>';

	if ($anb_default_location_count > 0) {
		$print_page .= $print_default_location;
	}

	foreach ( $anb_locations as $location ) {
		$anb_location_id = get_post_meta( $location->ID, "anb_location_id", true );
		$print_page .= '<div id="anb-location-id-' . $anb_location_id . '">' . PHP_EOL;
		foreach ($anbs as $alert_notice_values) {
			if ( $alert_notice_values->location_id == $anb_location_id ) {
				$alert_notice_id = $alert_notice_values->id;
				$anb_post_id = $alert_notice_values->post_ID;
				$anb_post = get_post($anb_post_id);
				$anb_enabled = $alert_notice_values->enabled;
				$anb_content = (isset($anb_post->post_content)) ? do_shortcode($anb_post->post_content) : null;
				$anb_design = $alert_notice_values->design_id;
				$anb_device = $alert_notice_values->device_class;
				$anb_animation = $alert_notice_values->animation_id;
				$anb_animation_out_id = $alert_notice_values->animation_out_id;
				$anb_delay = $alert_notice_values->delay;
				$anb_show_time = $alert_notice_values->show_time;
				$alert_notice_display_in = $alert_notice_values->display_in;

				$anb_close_button = get_post_meta( $anb_post_id, "click_on_close_button_anb_option", true );
				$anb_close_button_days = get_post_meta( $anb_post_id, "days_click_on_close_button_anb_option", true );
				$anb_limitations = get_post_meta( $anb_post_id, "limitations_anb_option", true );
				$anb_limitations_times = get_post_meta( $anb_post_id, "times_custom_limitations_anb_option", true );
				$anb_limitations_days = get_post_meta( $anb_post_id, "days_custom_limitations_anb_option", true );
				$anb_animation_out_post_option_animation_speed = get_post_meta( $anb_animation_out_id, "anb_animation_out_post_option_animation_speed", true );
				if ($anb_animation_out_post_option_animation_speed == '' || $anb_animation_out_post_option_animation_speed == null) {
					$anb_animation_out_post_option_animation_speed = 1;
				} elseif ($anb_animation_out_post_option_animation_speed < 0) {
					$anb_animation_out_post_option_animation_speed = 0;
				}

				if ( $anb_close_button == 'cancel-for' ) {
					$anb_close_button_val = $anb_close_button_days;
				} else {
					$anb_close_button_val = 0;
				}

				if ( $anb_limitations == 'custom-limitations' ) {
					$anb_limitations_times_val = $anb_limitations_times;
					$anb_limitations_days_val = $anb_limitations_days;
				} else {
					$anb_limitations_times_val = 0;
					$anb_limitations_days_val = 0;
				}

				if( anb_show($alert_notice_id,$get_page_id) && is_showing_post($alert_notice_id) ) {
					if ($anb_design == null) {
						$anb_design_class = 'anb anb-class-success';
						$anb_close_design = 'close-anb-success';
					} else {
						$anb_design_class = 'anb anb-class-' . $anb_design;
						$anb_close_design = 'close-anb-' . $anb_design;
					}
					if ($anb_animation == null || $anb_animation == 'default') {
						$anb_animation = 'anb-animation-default';
					} else {
						$anb_animation = 'anb-animation-id-' . $anb_animation;
					}
					$print_page .= '<div class="anb-bg">'.PHP_EOL;
					$print_page .= '	<div id="anb-id-' . $alert_notice_id . '" class="' . $anb_design_class . ' ' . $anb_device . ' ' . $anb_animation . ' delay" data-anb-id="' . $alert_notice_id . '" data-anb-delay="' . $anb_delay . '" data-anb-show-time="' . $anb_show_time . '" data-anb-limitations-times="' . $anb_limitations_times_val . '" data-anb-limitations-days="' . $anb_limitations_days_val . '" data-anb-animation-out-class="anb-animation-out-id-' . $anb_animation_out_id . '" data-anb-animation-out-speed="' . $anb_animation_out_post_option_animation_speed . '">'.PHP_EOL;
					if ( get_post_meta( $anb_design, "anb_close_button_position_option_disable", true ) != 'yes' ) {
						$print_page .= '	<span id="close-anb-id-' . $alert_notice_id . '" class="' . $anb_close_design . '" tabIndex="0" title="close box button" data-anb-close-button="' . $anb_close_button_val . '">&#x2715;</span>'.PHP_EOL;
					}
					$print_page .= wpautop($anb_content);
					$print_page .= '	</div>'.PHP_EOL;
					$print_page .= '</div>';
				}
			}
		}
		$print_page .= '</div>';
	}
	$print_page = (isset($print_page)) ? $print_page : '';
	echo $print_page;
}

function individual_control_meta_box() {

	$screen = get_current_screen();
	$get_page_id = get_the_ID();
	$disable_all_alerts_page = 'disable_all_alerts_page_' . $get_page_id;
	$get_disable_all_alerts_page = get_post_meta($get_page_id, $disable_all_alerts_page, true);
	global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes'; // do not forget about tables prefix
	$result = $wpdb->get_results( "SELECT * FROM $table_name");

	?>
	<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
	    <tbody>
	    <tr class="form-field">
	        <td>
				<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="<?php echo wp_create_nonce(YCANB_PLUGIN_URL.$get_page_id); ?>" />
				<input name="<?php echo $disable_all_alerts_page; ?>" type="checkbox" value="<?php echo $get_page_id; ?>" <?php  if(esc_attr( $get_disable_all_alerts_page ) == $get_page_id ) {echo 'checked="checked"';} ?> ><label for="disable_all_alerts_page"><strong><?php _e( 'Turn off all alerts for this page', 'alert-notice-boxes' ) ?></strong></label>
	        </td>
	    </tr>
		<tr class="form-field">
			<td>
				<strong><?php _e( 'Display this page these alerts', 'alert-notice-boxes' ) ?></strong><br>
				<?php
                foreach ($result as $alert_notice_values) {
                    $alert_id = $alert_notice_values->id;
					$alert_title = $alert_notice_values->title;
					$alert_display = 'display_alert_' . $alert_id . '_page_' . $get_page_id;
					$get_alert_display = get_post_meta($get_page_id, $alert_display, true);
                    ?>
                    <input name="display_alert_<?php echo $alert_id; ?>_page_<?php echo $get_page_id; ?>" type="checkbox" value="<?php echo $alert_id; ?>" <?php  if(esc_attr( $get_alert_display ) == $alert_id ) {echo 'checked="checked"';} ?> ><label for="hide_menu_checkbox"><?php echo $alert_title; ?></label></br>
                    <?php
                }
				?>
			</td>
	    </tr>
	    </tbody>
	</table>
	<?php

}

function add_individual_control_meta_box() {
	global $wp_post_types;
	$cpt_name = get_post_type(get_the_ID());
	$is_cpt_public = $wp_post_types[$cpt_name]->public;
    if( $is_cpt_public ) {
		add_meta_box("individual_control_meta_box_id", __( 'Alert settings for this page', 'alert-notice-boxes' ), array($this, 'individual_control_meta_box'), null, "side", "high", null);
	}
}

function save_individual_control_meta_box($post_id, $post, $update) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'alert_notice_boxes'; // do not forget about tables prefix
	$result = $wpdb->get_results( "SELECT * FROM $table_name");


	$get_page_id = get_the_ID();
	$disable_all_alerts_page = 'disable_all_alerts_page_' . $get_page_id;
	$get_disable_all_alerts_page = get_post_meta($get_page_id, $disable_all_alerts_page, true);

	$create_nonce = (isset($_POST['prevent_delete_meta_movetotrash']) ? $_POST['prevent_delete_meta_movetotrash'] : null);
	if (!wp_verify_nonce($create_nonce, YCANB_PLUGIN_URL.$get_page_id)) { return $get_page_id; }

	if(isset($_POST[$disable_all_alerts_page]) != "") {
		update_post_meta( $get_page_id, $disable_all_alerts_page, $_POST[$disable_all_alerts_page] );
	} else {
		if ($get_disable_all_alerts_page != '') {
			update_post_meta( $get_page_id, $disable_all_alerts_page, '' );
		}
	}

	foreach ($result as $alert_notice_values) {
		$alert_id = $alert_notice_values->id;
		$alert_title = $alert_notice_values->title;
		$alert_display = 'display_alert_' . $alert_id . '_page_' . $get_page_id;
		$get_alert_display = get_post_meta($get_page_id, $alert_display, true);

		if(isset($_POST[$alert_display]) != "") {
			update_post_meta( $get_page_id, $alert_display, $alert_id );
		} else {
			if ($get_alert_display != '') {
				update_post_meta( $get_page_id, $alert_display, '' );
			}
		}
	}
}

function anb_remove_post_meta_boxes( $hidden, $screen, $use_defaults ) {
	global $wp_meta_boxes;
	$cpt = 'anb'; // Modify this to your needs!

	if( $cpt === $screen->id && isset( $wp_meta_boxes[$cpt] ) ) {
		$tmp = array();
		foreach( (array) $wp_meta_boxes[$cpt] as $context_key => $context_item ) {
			foreach( $context_item as $priority_key => $priority_item ) {
				foreach( $priority_item as $metabox_key => $metabox_item ) {
					if ( $metabox_key != 'submitdiv' && $metabox_key != 'alert-notice-boxes-item-meta-box' ) {
						$tmp[] = $metabox_key;
					}
				}
			}
		}
		$hidden = $tmp;  // Override the current user option here.
	}
	return $hidden;
}

function duplicate_anb_as_draft() {
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'anb_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}

	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	$post = get_post( $post_id );
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	if (isset( $post ) && $post != null) {
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name . ' - ' . __('copy', 'alert-notice-boxes'),
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . ' - ' . __('copy', 'alert-notice-boxes'),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

	$new_post_id = wp_insert_post( $args );
	$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
	foreach ($taxonomies as $taxonomy) {
		$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
		wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	}

	$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $meta_info) {
			$meta_key = $meta_info->meta_key;
			$meta_value = addslashes($meta_info->meta_value);
			$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}
		$sql_query.= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	}

	$table_name = $wpdb->prefix . 'alert_notice_boxes'; // do not forget about tables prefix
	$alert_notice_box = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_ID = %d", $post_id) );

	$wpdb->insert ( $table_name, array(
		'post_ID' => $new_post_id,
		'title' => $post->post_title,
		'display_in' => $alert_notice_box->display_in,
		'location_id' => $alert_notice_box->location_id,
		'design_id' => $alert_notice_box->design_id,
		'animation_id' => $alert_notice_box->animation_id,
		'animation_out_id' => $alert_notice_box->animation_out_id,
		'delay' => $alert_notice_box->delay,
		'show_time' => $alert_notice_box->show_time,
		'enabled' => $alert_notice_box->enabled,
		'device_class' => $alert_notice_box->device_class
	));

	wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}

function duplicate_anb_link( $actions, $post ) {
	if ($post->post_type=='anb' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=anb_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'alert-notice-boxes') . '" rel="permalink">' . __('Duplicate', 'alert-notice-boxes') . '</a>';
		unset( $actions['view'] );
	}
	return $actions;
}




}

$YCanb = new YCanb;
