<?php

/*
*  Animations
*
*  @description: creat animations to boxs
*  @since: 1.2.6
*  @created: 24/09/16
*/

class YCanb_Animations {

function __construct() {
	add_action( 'admin_init', array( $this, 'anb_animations_capabilities' ) );
	add_action( 'anb_admin_menu', array($this, 'add_menus_to_anb' ) );
	add_action( 'init', array( $this, 'register_anb_animations' ) );
	add_action( 'add_meta_boxes', array($this, 'anb_animations_create_meta_boxes' ) );
	add_action( 'save_post', array( $this, 'save_anb_animations_item_meta_box' ), 10, 3);
	add_action( 'admin_action_anb_animations_duplicate_post_as_draft', array($this, 'duplicate_anb_animations_as_draft') );
	// filter
	add_filter( 'hidden_meta_boxes', array( $this, 'anb_animations_remove_post_meta_boxes' ), 10, 3 );
	add_filter( 'page_row_actions', array( $this, 'duplicate_anb_animations_link' ), 10, 2 );
	add_filter( 'parent_file', array( $this, 'anb_animations_cpt_parent_file' ) );
	add_filter( 'submenu_file', array( $this, 'anb_animations_cpt_submenu_file' ) );
	// register
	register_deactivation_hook( YCANB_PLUGIN_URL, array( $this, 'anb_animations_deactivation') );
}

function anb_animations_capabilities() {

	$role = get_role( 'administrator' );
	$role->add_cap( 'delete_anb_animations', true );
	$role->add_cap( 'delete_others_anb_animations', true );
	$role->add_cap( 'delete_private_anb_animations', true );
	$role->add_cap( 'delete_published_anb_animations', true );
	$role->add_cap( 'edit_anb_animations', true );
	$role->add_cap( 'edit_others_anb_animations', true );
	$role->add_cap( 'edit_private_anb_animations', true );
	$role->add_cap( 'edit_published_anb_animations', true );
	$role->add_cap( 'publish_anb_animations', true );
	$role->add_cap( 'read_private_anb_animations', true );
}

function anb_animations_deactivation() {

	$role = get_role( 'administrator' );
	$role->remove_cap( 'delete_anb_animations');
	$role->remove_cap( 'delete_others_anb_animations');
	$role->remove_cap( 'delete_private_anb_animations');
	$role->remove_cap( 'delete_published_anb_animations');
	$role->remove_cap( 'edit_anb_animations');
	$role->remove_cap( 'edit_others_anb_animations');
	$role->remove_cap( 'edit_private_anb_animations');
	$role->remove_cap( 'edit_published_anb_animations');
	$role->remove_cap( 'publish_anb_animations');
	$role->remove_cap( 'read_private_anb_animations');
}

function add_menus_to_anb() {
	add_submenu_page( 'edit.php?post_type=anb', __( 'Animations In', 'alert-notice-boxes' ), __( 'Animations In', 'alert-notice-boxes' ), 'edit_anb_animations', '/edit.php?post_type=anb_animations', null);
}

function anb_animations_cpt_parent_file( $parent_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_animations' == $current_screen->post_type ) {
        $parent_file = 'edit.php?post_type=anb';
    }
    return $parent_file;
}

function anb_animations_cpt_submenu_file( $submenu_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_animations' == $current_screen->post_type ) {
        $submenu_file = 'edit.php?post_type=anb_animations';
    }
    return $submenu_file;
}

function register_anb_animations() {
    register_post_type( 'anb_animations', array(
        'labels' => array(
		'name'               => __( 'Alert Notice Animations In', 'alert-notice-boxes' ),
		'singular_name'      => _x( 'Alert Notice Animations In', 'post type singular name', 'alert-notice-boxes' ),
		'menu_name'          => _x( 'Alert Notice Animations In', 'admin menu', 'alert-notice-boxes' ),
		'name_admin_bar'     => _x( 'Alert Notice Animations In', 'add new on admin bar', 'alert-notice-boxes' ),
		'add_new'            => _x( 'Add New', 'Post Type', 'alert-notice-boxes' ),
		'add_new_item'       => __( 'Add New Animation In', 'alert-notice-boxes' ),
		'new_item'           => __( 'New Box Animation In', 'alert-notice-boxes' ),
		'edit_item'          => __( 'Edit Box Animation In', 'alert-notice-boxes' ),
		'view_item'          => __( 'View Box Animation In', 'alert-notice-boxes' ),
		'all_items'          => __( 'Alert Notice Animations In', 'alert-notice-boxes' ),
		'search_items'       => __( 'Search', 'alert-notice-boxes' ),
		'parent_item_colon'  => __( 'Parent Alert Notice Box:', 'alert-notice-boxes' ),
		'not_found'          => __( 'No Box Animation In found.', 'alert-notice-boxes' ),
		'not_found_in_trash' => __( 'No Box Animation In found in Trash.', 'alert-notice-boxes' ),
		),

		// Frontend // Admin
		'supports'              => array( 'title'),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-megaphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'anb_animation',
		'map_meta_cap'          => true
    ) );
}

function anb_animations_remove_post_meta_boxes( $hidden, $screen, $use_defaults ) {
	global $wp_meta_boxes;
	$cpt = 'anb_animations'; // Modify this to your needs!

	if( $cpt === $screen->id && isset( $wp_meta_boxes[$cpt] ) ) {
		$tmp = array();
		foreach( (array) $wp_meta_boxes[$cpt] as $context_key => $context_item ) {
			foreach( $context_item as $priority_key => $priority_item ) {
				foreach( $priority_item as $metabox_key => $metabox_item ) {
					if ( $metabox_key != 'submitdiv' && $metabox_key != 'animations-boxes-item-meta-box' ) {
						$tmp[] = $metabox_key;
					}
				}
			}
		}
		$hidden = $tmp;  // Override the current user option here.
	}
	return $hidden;
}

function anb_animations_create_meta_boxes() {
    add_meta_box("animations-boxes-item-meta-box", __( 'Alert Notice Box settings', 'alert-notice-boxes' ), array($this, 'anb_animations_item_meta_box'), "anb_animations", "normal", "core", null);
}

function anb_animations_item_meta_box() {
	 wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	 $post = get_post();
	 $post->ID
	 ?>
		<form id="formanb" method="POST">
		<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
			<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="<?php echo wp_create_nonce(YCANB_PLUGIN_URL.$post->ID); ?>" />
			<tbody>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="anb_animation_post_option_animation_effect"><?php _e('Animation in effect', 'alert-notice-boxes')?></label>
						<p><?php _e('', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="anb_animation_post_option_animation_effect" class="anb_animation_post_option_animation_effect">
							<?php
							$get_animation_effects_value = get_post_meta( $post->ID, "anb_animation_post_option_animation_effect", true );
							$animation_effects_values = array(
								'default' => __('Default', 'alert-notice-boxes'),
								'bounce-side' => __('Bounce From Side', 'alert-notice-boxes'),
								'fade-in' => __('Fade In', 'alert-notice-boxes'),
								'fade-in-shake' => __('Fade In Shake', 'alert-notice-boxes'),
								'fade-in-rotate-shake' => __('Fade In Rotate Shake', 'alert-notice-boxes'),
								'scale-in' => __('Scale In', 'alert-notice-boxes'),
								'shake' => __('Shake', 'alert-notice-boxes'),
							);

							foreach($animation_effects_values as $key => $value) {
								if($key == $get_animation_effects_value) {
									?>
									<option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="position"><?php _e('Position', 'alert-notice-boxes')?></label>
						<p><?php _e('', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="anb_animation_post_option_side" class="anb_animation_post_option_side">
							<?php
							$side = get_post_meta( $post->ID, "anb_animation_post_option_side", true );
							$side_values = array('right', 'left');

							foreach($side_values as $key => $value) {
								if($value == $side) {
									?>
									<option selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
						<?php
						if ( get_post_meta( $post->ID, "anb_animation_post_option_side_value", true ) == '' ) {
						?>
							<input name="anb_animation_post_option_side_value" type="number" class="anb_animation_post_option_side_value" value="0" >
						<?php
						} else {
						?>
							<input name="anb_animation_post_option_side_value" type="number" class="anb_animation_post_option_side_value" value="<?php echo get_post_meta( $post->ID, "anb_animation_post_option_side_value", true ); ?>" >
						<?php
						}
						?>
						<select name="anb_animation_post_option_side_unit" class="anb_animation_post_option_side_unit">
							<?php
							$side_unit = get_post_meta( $post->ID, "anb_animation_post_option_side_unit", true );
							$side_units_values = array('px', '%');

							foreach($side_units_values as $key => $value) {
								if($value == $side_unit) {
									?>
									<option selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
						<br>
						<select name="anb_animation_post_option_height" class="anb_animation_post_option_height">
							<?php
							$height = get_post_meta( $post->ID, "anb_animation_post_option_height", true );
							$height_values = array('top', 'bottom');

							foreach($height_values as $key => $value) {
								if($value == $height) {
									?>
									<option selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
						<?php
						if ( get_post_meta( $post->ID, "anb_animation_post_option_height_value", true ) == '' ) {
						?>
						<input name="anb_animation_post_option_height_value" type="number" class="anb_animation_post_option_height_value" value="100" >
						<?php
						} else {
						?>
						<input name="anb_animation_post_option_height_value" type="number" class="anb_animation_post_option_height_value" value="<?php echo get_post_meta( $post->ID, "anb_animation_post_option_height_value", true ); ?>" >
						<?php
						}
						?>
						<select name="anb_animation_post_option_height_unit" class="anb_animation_post_option_height_unit">
							<?php
							$height_unit = get_post_meta( $post->ID, "anb_animation_post_option_height_unit", true );
							$height_units_values = array('px', '%');

							foreach($height_units_values as $key => $value) {
								if($value == $height_unit) {
									?>
									<option selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="animation_speed"><?php _e('Animation in speed', 'alert-notice-boxes')?></label>
						<p><?php _e('', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<input name="anb_animation_post_option_animation_speed" type="number" step="any" class="anb_animation_post_option_animation_speed" value="<?php echo get_post_meta( $post->ID, "anb_animation_post_option_animation_speed", true ); ?>" min="0" >
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="animation_timing"><?php _e('Animation in timing', 'alert-notice-boxes')?></label>
						<p><?php _e('', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="anb_animation_post_option_animation_timing" class="anb_animation_post_option_animation_timing">
							<?php
							$animation_timing_unit = get_post_meta( $post->ID, "anb_animation_post_option_animation_timing", true );
							$animation_timing_units_values = array('linear', 'ease-in', 'ease-out', 'ease-in-out');

							foreach($animation_timing_units_values as $key => $value) {
								if($value == $animation_timing_unit) {
									?>
									<option selected><?php echo $value; ?></option>
									<?php
								} else {
									?>
									<option><?php echo $value; ?></option>
									<?php
								}
							}
							?>
	            		</select>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	<?php
}

function save_anb_animations_item_meta_box( $post_id, $post) {
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;

	$slug = "anb_animations";
	if($slug != $post->post_type)
		return $post_id;

	$anb_animation_post_option_side = $_POST["anb_animation_post_option_side"];
	update_post_meta($post_id, "anb_animation_post_option_side", $anb_animation_post_option_side);
	$anb_animation_post_option_side_value = $_POST["anb_animation_post_option_side_value"];
	update_post_meta($post_id, "anb_animation_post_option_side_value", $anb_animation_post_option_side_value);
	$anb_animation_post_option_side_unit = $_POST["anb_animation_post_option_side_unit"];
	update_post_meta($post_id, "anb_animation_post_option_side_unit", $anb_animation_post_option_side_unit);
	$anb_animation_post_option_height = $_POST["anb_animation_post_option_height"];
	update_post_meta($post_id, "anb_animation_post_option_height", $anb_animation_post_option_height);
	$anb_animation_post_option_height_value = $_POST["anb_animation_post_option_height_value"];
	update_post_meta($post_id, "anb_animation_post_option_height_value", $anb_animation_post_option_height_value);
	$anb_animation_post_option_height_unit = $_POST["anb_animation_post_option_height_unit"];
	update_post_meta($post_id, "anb_animation_post_option_height_unit", $anb_animation_post_option_height_unit);
	$anb_animation_post_option_animation_speed = $_POST["anb_animation_post_option_animation_speed"];
	update_post_meta($post_id, "anb_animation_post_option_animation_speed", $anb_animation_post_option_animation_speed);
	$anb_animation_post_option_animation_timing = $_POST["anb_animation_post_option_animation_timing"];
	update_post_meta($post_id, "anb_animation_post_option_animation_timing", $anb_animation_post_option_animation_timing);
	$anb_animation_post_option_animation_effect = $_POST["anb_animation_post_option_animation_effect"];
	update_post_meta($post_id, "anb_animation_post_option_animation_effect", $anb_animation_post_option_animation_effect);



	$animations_style_css_file = fopen( YCANB_PLUGIN_DIR . "css/parts/animations_style.css", "w") or die("Unable to open file!");
	$posts_anb_animations = get_posts(array(
		'posts_per_page'=> -1,
		'post_type' => 'anb_animations',
	));

	$css_code = "\n";
	$css_code .= "\n";

	foreach ( $posts_anb_animations as $post_anb_animation ) {
		$post_anb_animation_id = $post_anb_animation->ID;
		$animation_effect = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_animation_effect", true );
		$animation_speed = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_animation_speed", true );
		$animation_side = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_side", true );
		$animation_side_value = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_side_value", true );
		$animation_side_unit = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_side_unit", true );
		$animation_height = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_height", true );
		$animation_height_value = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_height_value", true );
		$animation_height_unit = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_height_unit", true );
		$animation_timing = get_post_meta( $post_anb_animation_id, "anb_animation_post_option_animation_timing", true );
		if ($animation_speed == '' || $animation_speed == null) {
			$animation_speed = 1;
		} elseif ($animation_speed < 0) {
			$animation_speed = 0;
		}
		if ($animation_side == 'left') {
			$side_direction = '-';
			$side_direction_inverse = '';
		} else {
			$side_direction = '';
			$side_direction_inverse = '-';
		}
		if ($animation_height == 'top') {
			$height_direction = '-';
		} else {
			$height_direction = '';
		}
		if ($animation_effect == 'default') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'bounce-side') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "	}\n";
			$css_code .= "	60% {\n";
			$css_code .= "		transform: translateX(" . $side_direction_inverse . "30px);\n";
			$css_code .= "	}\n";
			$css_code .= "	80% {\n";
			$css_code .= "		transform: translateX(" . $side_direction . "10px);\n";
			$css_code .= "	}\n";
			$css_code .= "	90% {\n";
			$css_code .= "		transform: translateX(" . $side_direction_inverse . "5px);\n";
			$css_code .= "	}\n";
			$css_code .= "	95% {\n";
			$css_code .= "		transform: translateX(" . $side_direction . "2px);\n";
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'fade-in') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "		opacity: 0;\n" ;
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "		opacity: 1;\n" ;
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'scale-in') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: scale(0) translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "	}\n";
			$css_code .= "	60% {\n";
			$css_code .= "		transform: scale(1.2) translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "	70% {\n";
			$css_code .= "		transform: scale(0.9) translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "	80% {\n";
			$css_code .= "		transform: scale(1.1) translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "	90% {\n";
			$css_code .= "		transform: scale(0.95) translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "	100% {\n";
			$css_code .= "		transform: scale(1) translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'shake') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "	}\n";
			$css_code .= "	10, 90% {\n" ;
			$css_code .= "		transform: translate3d(-1px, 0, 0)\n" ;
			$css_code .= "	}\n";
			$css_code .= "	20, 80% {\n" ;
			$css_code .= "		transform: translate3d(2px, 0, 0)\n" ;
			$css_code .= "	}\n";
			$css_code .= "	30%, 50%, 70% {\n" ;
			$css_code .= "		transform: translate3d(-4px, 0, 0)\n" ;
			$css_code .= "	}\n";
			$css_code .= "	40%, 60% {\n" ;
			$css_code .= "		transform: translate3d(4px, 0, 0)\n" ;
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'fade-in-shake') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "		opacity: 0;\n" ;
			$css_code .= "	}\n";
			$css_code .= "	10%, 90% {\n" ;
			$css_code .= "		transform: translate3d(-1px, 0, 0);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	20%, 80% {\n" ;
			$css_code .= "		transform: translate3d(2px, 0, 0);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	30% {\n" ;
			$css_code .= "		transform: translate3d(-4px, 0, 0);\n" ;
			$css_code .= "		opacity: 1;\n" ;
			$css_code .= "	}\n";
			$css_code .= "	50%, 70% {\n" ;
			$css_code .= "		transform: translate3d(-4px, 0, 0);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	40%, 60% {\n" ;
			$css_code .= "		transform: translate3d(4px, 0, 0);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		} elseif ($animation_effect == 'fade-in-rotate-shake') {
			$css_code .= '.anb-animation-id-' . $post_anb_animation_id . " {\n" ;
			$css_code .= "\t";
			$css_code .= "-webkit-animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "\t";
			$css_code .= "animation: animation-" . $post_anb_animation_id . ' ' . $animation_speed . 's ' . $animation_timing . " both;\n" ;
			$css_code .= "}\n";
			$css_code .= "@keyframes animation-" . $post_anb_animation_id . " {\n" ;
			$css_code .= "	0% {\n" ;
			$css_code .= "		transform: translate(" . $side_direction . $animation_side_value . $animation_side_unit . "," . $height_direction . $animation_height_value . $animation_height_unit . ");\n" ;
			$css_code .= "		opacity: 0;\n" ;
			$css_code .= "	}\n";
			$css_code .= "	10%, 90% {\n" ;
			$css_code .= "		transform: rotate(5deg);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	20%, 80% {\n" ;
			$css_code .= "		transform: rotate(-5deg);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	30% {\n" ;
			$css_code .= "		transform: rotate(5deg);\n" ;
			$css_code .= "		opacity: 1;\n" ;
			$css_code .= "	}\n";
			$css_code .= "	50%, 70% {\n" ;
			$css_code .= "		transform: rotate(5deg);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	40%, 60% {\n" ;
			$css_code .= "		transform: rotate(-5deg);\n" ;
			$css_code .= "	}\n";
			$css_code .= "	100% {\n";
			$css_code .= "		transform: translate(0px,0px);\n";
			$css_code .= "		transform: rotate(0deg);\n";
			$css_code .= "	}\n" ;
			$css_code .= "}\n";
		}
	}
	fwrite($animations_style_css_file, $css_code);
	fclose($animations_style_css_file);

	create_anb_css_stylesheet();
}

function duplicate_anb_animations_as_draft() {
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'anb_animations_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
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

	wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}

function duplicate_anb_animations_link( $actions, $post ) {
	if ($post->post_type=='anb_animations' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=anb_animations_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'alert-notice-boxes') . '" rel="permalink">' . __('Duplicate', 'alert-notice-boxes') . '</a>';
		unset( $actions['view'] );
	}
	return $actions;
}

}

$YCanb_Animations = new YCanb_Animations;
