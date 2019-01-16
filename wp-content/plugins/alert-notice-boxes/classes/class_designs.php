<?php

/*
*  Designs
*
*  @description: creat designs to boxs
*  @since: 1.2.6
*  @created: 24/09/16
*/

class YCanb_Designs {

function __construct() {
	add_action( 'admin_init', array( $this, 'anb_designs_capabilities' ) );
	add_action( 'anb_admin_menu', array($this, 'add_menus_to_anb' ) );
	add_action( 'init', array( $this, 'register_anb_designs' ) );
	add_action( 'add_meta_boxes', array($this, 'anb_designs_create_meta_boxes' ) );
	add_action( 'save_post', array( $this, 'save_anb_designs_item_meta_box' ), 10, 3);
	add_action( 'admin_action_anb_designs_duplicate_post_as_draft', array($this, 'duplicate_anb_designs_as_draft') );
	// filter
	add_filter( 'hidden_meta_boxes', array( $this, 'anb_designs_remove_post_meta_boxes' ), 10, 3 );
	add_filter( 'page_row_actions', array( $this, 'duplicate_anb_designs_link' ), 10, 2 );
	add_filter( 'parent_file', array( $this, 'anb_designs_cpt_parent_file' ) );
	add_filter( 'submenu_file', array( $this, 'anb_designs_cpt_submenu_file' ) );
	// register
	register_deactivation_hook( YCANB_PLUGIN_URL, array( $this, 'anb_designs_deactivation') );
}

function anb_designs_capabilities() {

	$role = get_role( 'administrator' );
	$role->add_cap( 'delete_anb_designs', true );
	$role->add_cap( 'delete_others_anb_designs', true );
	$role->add_cap( 'delete_private_anb_designs', true );
	$role->add_cap( 'delete_published_anb_designs', true );
	$role->add_cap( 'edit_anb_designs', true );
	$role->add_cap( 'edit_others_anb_designs', true );
	$role->add_cap( 'edit_private_anb_designs', true );
	$role->add_cap( 'edit_published_anb_designs', true );
	$role->add_cap( 'publish_anb_designs', true );
	$role->add_cap( 'read_private_anb_designs', true );
}

function anb_designs_deactivation() {

	$role = get_role( 'administrator' );
	$role->remove_cap( 'delete_anb_designs');
	$role->remove_cap( 'delete_others_anb_designs');
	$role->remove_cap( 'delete_private_anb_designs');
	$role->remove_cap( 'delete_published_anb_designs');
	$role->remove_cap( 'edit_anb_designs');
	$role->remove_cap( 'edit_others_anb_designs');
	$role->remove_cap( 'edit_private_anb_designs');
	$role->remove_cap( 'edit_published_anb_designs');
	$role->remove_cap( 'publish_anb_designs');
	$role->remove_cap( 'read_private_anb_designs');
}

function add_menus_to_anb() {
    add_submenu_page( 'edit.php?post_type=anb', __( 'Designs', 'alert-notice-boxes' ), __( 'Designs', 'alert-notice-boxes' ), 'edit_anb_designs', 'edit.php?post_type=anb_designs' );
}

function anb_designs_cpt_parent_file( $parent_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_designs' == $current_screen->post_type ) {
        $parent_file = 'edit.php?post_type=anb';
    }
    return $parent_file;
}

function anb_designs_cpt_submenu_file( $submenu_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_designs' == $current_screen->post_type ) {
        $submenu_file = 'edit.php?post_type=anb_designs';
    }
    return $submenu_file;
}

function register_anb_designs() {
    register_post_type( 'anb_designs', array(
        'labels' => array(
		'name'               => __( 'Alert Notice Designs', 'alert-notice-boxes' ),
		'singular_name'      => _x( 'Alert Notice Designs', 'post type singular name', 'alert-notice-boxes' ),
		'menu_name'          => _x( 'Alert Notice Designs', 'admin menu', 'alert-notice-boxes' ),
		'name_admin_bar'     => _x( 'Alert Notice Designs', 'add new on admin bar', 'alert-notice-boxes' ),
		'add_new'            => _x( 'Add New', 'Post Type', 'alert-notice-boxes' ),
		'add_new_item'       => __( 'Add New Design', 'alert-notice-boxes' ),
		'new_item'           => __( 'New Box Design', 'alert-notice-boxes' ),
		'edit_item'          => __( 'Edit Box Design', 'alert-notice-boxes' ),
		'view_item'          => __( 'View Box Design', 'alert-notice-boxes' ),
		'all_items'          => __( 'Alert Notice Designs', 'alert-notice-boxes' ),
		'search_items'       => __( 'Search', 'alert-notice-boxes' ),
		'parent_item_colon'  => __( 'Parent Alert Notice Box:', 'alert-notice-boxes' ),
		'not_found'          => __( 'No Box Design found.', 'alert-notice-boxes' ),
		'not_found_in_trash' => __( 'No Box Design found in Trash.', 'alert-notice-boxes' ),
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
		'capability_type'       => 'anb_design',
		'map_meta_cap'          => true
    ) );
}

function anb_designs_remove_post_meta_boxes( $hidden, $screen, $use_defaults ) {
	global $wp_meta_boxes;
	$cpt = 'anb_designs'; // Modify this to your needs!

	if( $cpt === $screen->id && isset( $wp_meta_boxes[$cpt] ) ) {
		$tmp = array();
		foreach( (array) $wp_meta_boxes[$cpt] as $context_key => $context_item ) {
			foreach( $context_item as $priority_key => $priority_item ) {
				foreach( $priority_item as $metabox_key => $metabox_item ) {
					if ( $metabox_key != 'submitdiv' && $metabox_key != 'designs-boxes-item-meta-box' ) {
						$tmp[] = $metabox_key;
					}
				}
			}
		}
		$hidden = $tmp;  // Override the current user option here.
	}
	return $hidden;
}

function anb_designs_create_meta_boxes() {
    add_meta_box("designs-boxes-item-meta-box", __( 'Alert Notice Box settings', 'alert-notice-boxes' ), array($this, 'anb_designs_item_meta_box'), "anb_designs", "normal", "core", null);
}

function anb_designs_item_meta_box() {
	 wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	 $post = get_post();
	 $post->ID
	 ?>
		<form id="formanb" method="POST">
		<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="<?php echo wp_create_nonce(YCANB_PLUGIN_URL.$post->ID); ?>" />
		<div class="anb-design-settings">
			<ul class="tab">
				<li><span class="tablinks active" data-opentab="BoxDesign"><?php _e( 'Box Design', 'alert-notice-boxes' ) ?></span></li>
				<li><span class="tablinks" data-opentab="CloseButton"><?php _e( 'Close Button', 'alert-notice-boxes' ) ?></span></li>
			</ul>
			<div id="BoxDesign" class="tabcontent" style="display: block;">
				<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
					<tbody>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="text-color"><?php _e('Text color', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_text_color" type="text" class="anb_design_post_option_text_color" value="<?php echo get_post_meta( $post->ID, "anb_design_post_option_text_color", true ); ?>">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="link-color"><?php _e('Link color', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_link_color" type="text" class="anb_design_post_option_link_color" value="<?php echo get_post_meta( $post->ID, "anb_design_post_option_link_color", true ); ?>">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="font-size"><?php _e('Font size', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_font_size" type="number" class="anb_design_post_option_font_size" value="<?php echo get_post_meta( $post->ID, "anb_design_post_option_font_size", true ); ?>">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="background-color"><?php _e('Background color', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_background_color" type="text" class="anb_design_post_option_background_color" value="<?php echo get_post_meta( $post->ID, "anb_design_post_option_background_color", true ); ?>">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="background-image"><?php _e('Background image', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input id="anb_design_post_option_background_image" name="anb_design_post_option_background_image" type="text" value="<?php echo get_post_meta( $post->ID, "anb_design_post_option_background_image", true ); ?>" hidden />
							<img id="image_anb_design_background_image" src="<?php echo get_post_meta( $post->ID, "anb_design_post_option_background_image", true ); ?>" alt="" target="_blank" rel="external" style="max-width: 200px;"><br>
							<input id="upload-button" type="button" class="button" value="<?php _e('Upload Image', 'alert-notice-boxes')?>" /> <span class="button remove-image" id="reset_logo_upload" rel="logo_upload"><?php _e('Remove', 'alert-notice-boxes')?></span>
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="background-size"><?php _e('Background size', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<select name="anb_design_post_option_background_size" class="anb_design_post_option_background_size">
								<?php
								$background_size_option = get_post_meta( $post->ID, "anb_design_post_option_background_size", true );
								$background_size_options_values = array('', 'auto', 'cover', 'contain');

								foreach($background_size_options_values as $key => $value) {
									if($value == $background_size_option) {
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
							<label for="background-repeat"><?php _e('Background repeat', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<select name="anb_design_post_option_background_repeat" class="anb_design_post_option_background_repeat">
								<?php
								$background_repeat_option = get_post_meta( $post->ID, "anb_design_post_option_background_repeat", true );
								$background_repeat_options_values = array('', 'no-repeat', 'repeat');

								foreach($background_repeat_options_values as $key => $value) {
									if($value == $background_repeat_option) {
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
							<label for="border-radius"><?php _e('Border radius', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_border_top_left_radius" type="number" class="anb_design_post_option_border_radius" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_border_top_left_radius", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_border_top_left_radius", true ); } else { echo '5'; } ?>" min="0" > ↖
							<input name="anb_design_post_option_border_top_right_radius" type="number" class="anb_design_post_option_border_radius" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_border_top_right_radius", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_border_top_right_radius", true ); } else { echo '5'; } ?>" min="0" > ↗
							<input name="anb_design_post_option_border_bottom_right_radius" type="number" class="anb_design_post_option_border_radius" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_border_bottom_right_radius", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_border_bottom_right_radius", true ); } else { echo '5'; } ?>" min="0" > ↘
							<input name="anb_design_post_option_border_bottom_left_radius" type="number" class="anb_design_post_option_border_radius" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_border_bottom_left_radius", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_border_bottom_left_radius", true ); } else { echo '5'; } ?>" min="0" > ↙
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="padding"><?php _e('Padding', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_padding_top" type="number" class="anb_design_post_option_padding" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_padding_top", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_padding_top", true ); } else { echo '20'; } ?>" min="0" > ↑
							<input name="anb_design_post_option_padding_right" type="number" class="anb_design_post_option_padding" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_padding_right", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_padding_right", true ); } else { echo '15'; } ?>" min="0" > →
							<input name="anb_design_post_option_padding_bottom" type="number" class="anb_design_post_option_padding" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_padding_bottom", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_padding_bottom", true ); } else { echo '15'; } ?>" min="0" > ↓
							<input name="anb_design_post_option_padding_left" type="number" class="anb_design_post_option_padding" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_padding_left", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_padding_left", true ); } else { echo '15'; } ?>" min="0" > ←
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="margin"><?php _e('Margin', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_margin_top" type="number" class="anb_design_post_option_margin" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_margin_top", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_margin_top", true ); } else { echo '10'; } ?>" > ↑
							<input name="anb_design_post_option_margin_right" type="number" class="anb_design_post_option_margin" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_margin_right", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_margin_right", true ); } else { echo '0'; } ?>" > →
							<input name="anb_design_post_option_margin_bottom" type="number" class="anb_design_post_option_margin" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_margin_bottom", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_margin_bottom", true ); } else { echo '0'; } ?>" > ↓
							<input name="anb_design_post_option_margin_left" type="number" class="anb_design_post_option_margin" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_margin_left", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_margin_left", true ); } else { echo '0'; } ?>" > ←
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="opacity"><?php _e('Opacity', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_design_post_option_opacity" type="range" class="anb_design_post_option_opacity" step="0.01" value="<?php if ( get_post_meta( $post->ID, "anb_design_post_option_opacity", true ) != '' ) { echo get_post_meta( $post->ID, "anb_design_post_option_opacity", true ); } else { echo '1'; } ?>" min="0" max="1">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div id="CloseButton" class="tabcontent">
				<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
					<tbody>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="disable_close"><?php _e('Disable Close Button', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_close_button_position_option_disable" type="checkbox" value="yes" <?php  if ( get_post_meta( $post->ID, "anb_close_button_position_option_disable", true ) == 'yes' ) {echo 'checked="checked"';} ?>><label for="anb_close_button_position_option_disable"><?php _e('Disable', 'alert-notice-boxes') ?></label>
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="button-position"><?php _e('Button Position', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<select name="anb_close_button_position_option_side" class="close_button_position_field">
								<?php
								$side = get_post_meta( $post->ID, "anb_close_button_position_option_side", true );
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
							if ( get_post_meta( $post->ID, "anb_close_button_position_option_side_value", true ) == '' ) {
							?>
								<input name="anb_close_button_position_option_side_value" type="number" class="close_button_position_field" value="5" >
							<?php
							} else {
							?>
								<input name="anb_close_button_position_option_side_value" type="number" class="close_button_position_field" value="<?php echo get_post_meta( $post->ID, "anb_close_button_position_option_side_value", true ); ?>" >
							<?php
							}
							?>
							<select name="anb_close_button_position_option_side_unit" class="close_button_position_field">
								<?php
								$side_unit = get_post_meta( $post->ID, "anb_close_button_position_option_side_unit", true );
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
							<select name="anb_close_button_position_option_height" class="close_button_position_field">
								<?php
								$height = get_post_meta( $post->ID, "anb_close_button_position_option_height", true );
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
							if ( get_post_meta( $post->ID, "anb_close_button_position_option_height_value", true ) == '' ) {
							?>
							<input name="anb_close_button_position_option_height_value" type="number" class="close_button_position_field" value="5" >
							<?php
							} else {
							?>
							<input name="anb_close_button_position_option_height_value" type="number" class="close_button_position_field" value="<?php echo get_post_meta( $post->ID, "anb_close_button_position_option_height_value", true ); ?>" >
							<?php
							}
							?>
							<select name="anb_close_button_position_option_height_unit" class="close_button_position_field">
								<?php
								$height_unit = get_post_meta( $post->ID, "anb_close_button_position_option_height_unit", true );
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
							<label for="ButtonSize"><?php _e('Button Size', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_close_button_size" type="range" class="anb_close_button_size" step="1" value="<?php if ( get_post_meta( $post->ID, "anb_close_button_size", true ) != '' ) { echo get_post_meta( $post->ID, "anb_close_button_size", true ); } else { echo '15'; } ?>" min="10" max="64">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="close-button-color"><?php _e('Button color', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_close_button_color" type="text" class="anb_close_button_color" value="<?php echo get_post_meta( $post->ID, "anb_close_button_color", true ); ?>">
						</td>
					</tr>
					<tr class="form-field">
						<th valign="top" scope="row">
							<label for="close-button-background-color"><?php _e('Button Background color', 'alert-notice-boxes')?></label>
							<p><?php _e('', 'alert-notice-boxes')?></p>
						</th>
						<td>
							<input name="anb_close_button_background_color" type="text" class="anb_close_button_background_color" value="<?php echo get_post_meta( $post->ID, "anb_close_button_background_color", true ); ?>">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		</form>
	<?php
}

function save_anb_designs_item_meta_box( $post_id, $post) {
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;

	$slug = "anb_designs";
	if($slug != $post->post_type)
		return $post_id;

	update_post_meta($post_id, "anb_design_post_option_class_name", 'anb-class-' . $post_id);
	$anb_design_post_option_text_color = $_POST["anb_design_post_option_text_color"];
	update_post_meta($post_id, "anb_design_post_option_text_color", $anb_design_post_option_text_color);
	$anb_design_post_option_link_color = $_POST["anb_design_post_option_link_color"];
	update_post_meta($post_id, "anb_design_post_option_link_color", $anb_design_post_option_link_color);
	$anb_design_post_option_font_size = $_POST["anb_design_post_option_font_size"];
	update_post_meta($post_id, "anb_design_post_option_font_size", $anb_design_post_option_font_size);
	$anb_design_post_option_background_color = $_POST["anb_design_post_option_background_color"];
	update_post_meta($post_id, "anb_design_post_option_background_color", $anb_design_post_option_background_color);
	$anb_design_post_option_background_image = $_POST["anb_design_post_option_background_image"];
	update_post_meta($post_id, "anb_design_post_option_background_image", $anb_design_post_option_background_image);
	$anb_design_post_option_background_size = $_POST["anb_design_post_option_background_size"];
	update_post_meta($post_id, "anb_design_post_option_background_size", $anb_design_post_option_background_size);
	$anb_design_post_option_background_repeat = $_POST["anb_design_post_option_background_repeat"];
	update_post_meta($post_id, "anb_design_post_option_background_repeat", $anb_design_post_option_background_repeat);
	$anb_design_post_option_border_top_left_radius = $_POST["anb_design_post_option_border_top_left_radius"];
	update_post_meta($post_id, "anb_design_post_option_border_top_left_radius", $anb_design_post_option_border_top_left_radius);
	$anb_design_post_option_border_top_right_radius = $_POST["anb_design_post_option_border_top_right_radius"];
	update_post_meta($post_id, "anb_design_post_option_border_top_right_radius", $anb_design_post_option_border_top_right_radius);
	$anb_design_post_option_border_bottom_right_radius = $_POST["anb_design_post_option_border_bottom_right_radius"];
	update_post_meta($post_id, "anb_design_post_option_border_bottom_right_radius", $anb_design_post_option_border_bottom_right_radius);
	$anb_design_post_option_border_bottom_left_radius = $_POST["anb_design_post_option_border_bottom_left_radius"];
	update_post_meta($post_id, "anb_design_post_option_border_bottom_left_radius", $anb_design_post_option_border_bottom_left_radius);
	$anb_design_post_option_padding_top = $_POST["anb_design_post_option_padding_top"];
	update_post_meta($post_id, "anb_design_post_option_padding_top", $anb_design_post_option_padding_top);
	$anb_design_post_option_padding_right = $_POST["anb_design_post_option_padding_right"];
	update_post_meta($post_id, "anb_design_post_option_padding_right", $anb_design_post_option_padding_right);
	$anb_design_post_option_padding_bottom = $_POST["anb_design_post_option_padding_bottom"];
	update_post_meta($post_id, "anb_design_post_option_padding_bottom", $anb_design_post_option_padding_bottom);
	$anb_design_post_option_padding_left = $_POST["anb_design_post_option_padding_left"];
	update_post_meta($post_id, "anb_design_post_option_padding_left", $anb_design_post_option_padding_left);
	$anb_design_post_option_margin_top = $_POST["anb_design_post_option_margin_top"];
	update_post_meta($post_id, "anb_design_post_option_margin_top", $anb_design_post_option_margin_top);
	$anb_design_post_option_margin_right = $_POST["anb_design_post_option_margin_right"];
	update_post_meta($post_id, "anb_design_post_option_margin_right", $anb_design_post_option_margin_right);
	$anb_design_post_option_margin_bottom = $_POST["anb_design_post_option_margin_bottom"];
	update_post_meta($post_id, "anb_design_post_option_margin_bottom", $anb_design_post_option_margin_bottom);
	$anb_design_post_option_margin_left = $_POST["anb_design_post_option_margin_left"];
	update_post_meta($post_id, "anb_design_post_option_margin_left", $anb_design_post_option_margin_left);
	$anb_design_post_option_opacity = $_POST["anb_design_post_option_opacity"];
	update_post_meta($post_id, "anb_design_post_option_opacity", $anb_design_post_option_opacity);
	$anb_close_button_position_option_side = $_POST["anb_close_button_position_option_side"];
	update_post_meta($post_id, "anb_close_button_position_option_side", $anb_close_button_position_option_side);
	$anb_close_button_position_option_side_value = $_POST["anb_close_button_position_option_side_value"];
	update_post_meta($post_id, "anb_close_button_position_option_side_value", $anb_close_button_position_option_side_value);
	$anb_close_button_position_option_side_unit = $_POST["anb_close_button_position_option_side_unit"];
	update_post_meta($post_id, "anb_close_button_position_option_side_unit", $anb_close_button_position_option_side_unit);
	$anb_close_button_position_option_height = $_POST["anb_close_button_position_option_height"];
	update_post_meta($post_id, "anb_close_button_position_option_height", $anb_close_button_position_option_height);
	$anb_close_button_position_option_height_value = $_POST["anb_close_button_position_option_height_value"];
	update_post_meta($post_id, "anb_close_button_position_option_height_value", $anb_close_button_position_option_height_value);
	$anb_close_button_position_option_height_unit = $_POST["anb_close_button_position_option_height_unit"];
	update_post_meta($post_id, "anb_close_button_position_option_height_unit", $anb_close_button_position_option_height_unit);
	$anb_close_button_size = $_POST["anb_close_button_size"];
	update_post_meta($post_id, "anb_close_button_size", $anb_close_button_size);
	$anb_close_button_color = $_POST["anb_close_button_color"];
	update_post_meta($post_id, "anb_close_button_color", $anb_close_button_color);
	$anb_close_button_background_color = $_POST["anb_close_button_background_color"];
	update_post_meta($post_id, "anb_close_button_background_color", $anb_close_button_background_color);
	$anb_close_button_position_option_disable = (isset($_POST["anb_close_button_position_option_disable"])) ? $_POST["anb_close_button_position_option_disable"] : null;
	update_post_meta($post_id, "anb_close_button_position_option_disable", $anb_close_button_position_option_disable);

	$designs_style_css_file = fopen( YCANB_PLUGIN_DIR . "css/parts/designs_style.css", "w") or die("Unable to open file!");
	$posts_anb_designs = get_posts(array(
		'posts_per_page'=> -1,
		'post_type' => 'anb_designs',
	));

	$css_code = "\n";
	$css_code .= "\n";

	foreach ( $posts_anb_designs as $post_anb_design ) {
		$css_code .= '.' . get_post_meta( $post_anb_design->ID, "anb_design_post_option_class_name", true ) . " {\n" ;
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_text_color", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "color: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_text_color", true ) . "!important;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_font_size", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "font-size: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_font_size", true ) . "px!important;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_color", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "background-color: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_color", true ) . ";\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_image", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "background-image: url(" . get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_image", true ) . ");\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_size", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "background-size: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_size", true ) . ";\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_repeat", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "background-repeat: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_background_repeat", true ) . ";\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_top_left_radius", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "border-top-left-radius: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_top_left_radius", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_top_right_radius", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "border-top-right-radius: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_top_right_radius", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_bottom_right_radius", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "border-bottom-right-radius: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_bottom_right_radius", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_bottom_left_radius", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "border-bottom-left-radius: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_border_bottom_left_radius", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_top", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "padding-top: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_top", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_right", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "padding-right: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_right", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_bottom", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "padding-bottom: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_bottom", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_left", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "padding-left: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_padding_left", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_top", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "margin-top: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_top", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_right", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "margin-right: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_right", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_bottom", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "margin-bottom: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_bottom", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_left", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "margin-left: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_margin_left", true ) . "px;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_opacity", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "opacity: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_opacity", true ) . ";\n" ;
		}
		$css_code .= "}\n";

		if ( get_post_meta( $post_anb_design->ID, "anb_design_post_option_link_color", true ) != '' ) {
			$css_code .= '.' . get_post_meta( $post_anb_design->ID, "anb_design_post_option_class_name", true ) . " a {\n" ;
			$css_code .= "\t";
			$css_code .= "color: " . get_post_meta( $post_anb_design->ID, "anb_design_post_option_link_color", true ) . "!important;\n" ;
			$css_code .= "}\n";
		}

		$css_code .= '.close-anb-' . $post_anb_design->ID . " {\n" ;
		$css_code .= "\t";
		$css_code .= get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_side", true ) . ': ' . get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_side_value", true ) . get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_side_unit", true ) .";\n" ;
		$css_code .= "\t";
		$css_code .= get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_height", true ) . ': ' . get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_height_value", true ) . get_post_meta( $post_anb_design->ID, "anb_close_button_position_option_height_unit", true ) .";\n" ;
		$css_code .= "\t";
		$css_code .= "font-size: " . get_post_meta( $post_anb_design->ID, "anb_close_button_size", true ) . "px!important;\n" ;
		$css_code .= "\t";
		$css_code .= "line-height: " . get_post_meta( $post_anb_design->ID, "anb_close_button_size", true ) . "px!important;\n" ;
		$css_code .= "\t";
		$css_code .= "width: " . get_post_meta( $post_anb_design->ID, "anb_close_button_size", true ) . "px!important;\n" ;
		if ( get_post_meta( $post_anb_design->ID, "anb_close_button_color", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "color: " . get_post_meta( $post_anb_design->ID, "anb_close_button_color", true ) . "!important;\n" ;
		}
		if ( get_post_meta( $post_anb_design->ID, "anb_close_button_background_color", true ) != '' ) {
			$css_code .= "\t";
			$css_code .= "background-color: " . get_post_meta( $post_anb_design->ID, "anb_close_button_background_color", true ) . "!important;\n" ;
		}
		$css_code .= "}\n";
	}

	fwrite($designs_style_css_file, $css_code);
	fclose($designs_style_css_file);

	create_anb_css_stylesheet();
}

function duplicate_anb_designs_as_draft() {
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'anb_designs_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
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

function duplicate_anb_designs_link( $actions, $post ) {
	if ($post->post_type=='anb_designs' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=anb_designs_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'alert-notice-boxes') . '" rel="permalink">' . __('Duplicate', 'alert-notice-boxes') . '</a>';
		unset( $actions['view'] );
	}
	return $actions;
}

}

$YCanb_Designs = new YCanb_Designs;
