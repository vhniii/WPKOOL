<?php

/*
*  Locations
*
*  @description: creat locations to boxs
*  @since: 1.2.6
*  @created: 24/09/16
*/

class YCanb_Locations {

function __construct() {
	add_action( 'admin_init', array( $this, 'anb_locations_capabilities' ) );
	add_action( 'anb_admin_menu', array($this, 'add_menus_to_anb' ) );
	add_action( 'init', array( $this, 'register_anb_locations' ) );
	add_action( 'add_meta_boxes', array($this, 'anb_locations_create_meta_boxes' ) );
	add_action( 'save_post', array( $this, 'save_anb_locations_item_meta_box' ), 10, 3);
	add_action( 'admin_action_anb_locations_duplicate_post_as_draft', array($this, 'duplicate_anb_locations_as_draft') );
	// filter
	add_filter( 'hidden_meta_boxes', array( $this, 'anb_locations_remove_post_meta_boxes' ), 10, 3 );
	add_filter( 'page_row_actions', array( $this, 'duplicate_anb_locations_link' ), 10, 2 );
	add_filter( 'parent_file', array( $this, 'anb_locations_cpt_parent_file' ) );
	add_filter( 'submenu_file', array( $this, 'anb_locations_cpt_submenu_file' ) );
	// register
	register_deactivation_hook( YCANB_PLUGIN_URL, array( $this, 'anb_locations_deactivation') );
}

function anb_locations_capabilities() {

	$role = get_role( 'administrator' );
	$role->add_cap( 'delete_anb_locations', true );
	$role->add_cap( 'delete_others_anb_locations', true );
	$role->add_cap( 'delete_private_anb_locations', true );
	$role->add_cap( 'delete_published_anb_locations', true );
	$role->add_cap( 'edit_anb_locations', true );
	$role->add_cap( 'edit_others_anb_locations', true );
	$role->add_cap( 'edit_private_anb_locations', true );
	$role->add_cap( 'edit_published_anb_locations', true );
	$role->add_cap( 'publish_anb_locations', true );
	$role->add_cap( 'read_private_anb_locations', true );
}

function anb_locations_deactivation() {

	$role = get_role( 'administrator' );
	$role->remove_cap( 'delete_anb_locations');
	$role->remove_cap( 'delete_others_anb_locations');
	$role->remove_cap( 'delete_private_anb_locations');
	$role->remove_cap( 'delete_published_anb_locations');
	$role->remove_cap( 'edit_anb_locations');
	$role->remove_cap( 'edit_others_anb_locations');
	$role->remove_cap( 'edit_private_anb_locations');
	$role->remove_cap( 'edit_published_anb_locations');
	$role->remove_cap( 'publish_anb_locations');
	$role->remove_cap( 'read_private_anb_locations');
}

function add_menus_to_anb() {
    add_submenu_page( 'edit.php?post_type=anb', __( 'Locations', 'alert-notice-boxes' ), __( 'Locations', 'alert-notice-boxes' ), 'edit_anb_locations', 'edit.php?post_type=anb_locations' );
}

function register_anb_locations() {
    register_post_type( 'anb_locations', array(
        'labels' => array(
		'name'               => __( 'Alert Notice Locations', 'alert-notice-boxes' ),
		'singular_name'      => _x( 'Alert Notice Locations', 'post type singular name', 'alert-notice-boxes' ),
		'menu_name'          => _x( 'Alert Notice Locations', 'admin menu', 'alert-notice-boxes' ),
		'name_admin_bar'     => _x( 'Alert Notice Locations', 'add new on admin bar', 'alert-notice-boxes' ),
		'add_new'            => _x( 'Add New', 'Post Type', 'alert-notice-boxes' ),
		'add_new_item'       => __( 'Add New Location', 'alert-notice-boxes' ),
		'new_item'           => __( 'New Box Location', 'alert-notice-boxes' ),
		'edit_item'          => __( 'Edit Box Location', 'alert-notice-boxes' ),
		'view_item'          => __( 'View Box Location', 'alert-notice-boxes' ),
		'all_items'          => __( 'Alert Notice Locations', 'alert-notice-boxes' ),
		'search_items'       => __( 'Search', 'alert-notice-boxes' ),
		'parent_item_colon'  => __( 'Parent Alert Notice Box:', 'alert-notice-boxes' ),
		'not_found'          => __( 'No Box Location found.', 'alert-notice-boxes' ),
		'not_found_in_trash' => __( 'No Box Location found in Trash.', 'alert-notice-boxes' ),
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
		'capability_type'       => 'anb_location',
		'map_meta_cap'          => true
    ) );
}

function anb_locations_remove_post_meta_boxes( $hidden, $screen, $use_defaults ) {
	global $wp_meta_boxes;
	$cpt = 'anb_locations'; // Modify this to your needs!

	if( $cpt === $screen->id && isset( $wp_meta_boxes[$cpt] ) ) {
		$tmp = array();
		foreach( (array) $wp_meta_boxes[$cpt] as $context_key => $context_item ) {
			foreach( $context_item as $priority_key => $priority_item ) {
				foreach( $priority_item as $metabox_key => $metabox_item ) {
					if ( $metabox_key != 'submitdiv' && $metabox_key != 'locations-boxes-item-meta-box' ) {
						$tmp[] = $metabox_key;
					}
				}
			}
		}
		$hidden = $tmp;  // Override the current user option here.
	}
	return $hidden;
}

function anb_locations_create_meta_boxes() {
    add_meta_box("locations-boxes-item-meta-box", __( 'Alert Notice Box settings', 'alert-notice-boxes' ), array($this, 'anb_locations_item_meta_box'), "anb_locations", "normal", "core", null);
}

function anb_locations_cpt_parent_file( $parent_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_locations' == $current_screen->post_type ) {
        $parent_file = 'edit.php?post_type=anb';
    }
    return $parent_file;
}

function anb_locations_cpt_submenu_file( $submenu_file ){
    global $current_screen, $self;
    if ( in_array( $current_screen->base, array( 'post', 'edit' ) ) && 'anb_locations' == $current_screen->post_type ) {
        $submenu_file = 'edit.php?post_type=anb_locations';
    }
    return $submenu_file;
}

function anb_locations_item_meta_box() {
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
					<label for="width"><?php _e('Width', 'alert-notice-boxes')?></label>
					<p><?php _e('', 'alert-notice-boxes')?></p>
				</th>
				<td>
					<input name="anb_location_post_option_width" type="number" class="anb_location_post_option_width" value="<?php echo get_post_meta( $post->ID, "anb_location_post_option_width", true ); ?>" min="1" >
					<select name="anb_location_post_option_width_unit" class="anb_location_post_option_width_unit">
						<?php
						$width_unit = get_post_meta( $post->ID, "anb_location_post_option_width_unit", true );
						$width_units_values = array('px', '%');

						foreach($width_units_values as $key => $value) {
							if($value == $width_unit) {
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
					<label for="position"><?php _e('Position', 'alert-notice-boxes')?></label>
					<p><?php _e('', 'alert-notice-boxes')?></p>
				</th>
				<td>
					<select id="anb_location_post_option_side" name="anb_location_post_option_side" class="anb_location_post_option_side" data-hide-options>
						<?php
						$side = get_post_meta( $post->ID, "anb_location_post_option_side", true );
						$side_values = array('right', 'left', 'center');

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
					if ( get_post_meta( $post->ID, "anb_location_post_option_side_value", true ) == '' ) {
					?>
						<input id="anb_location_post_option_side_value" name="anb_location_post_option_side_value" type="number" class="anb_location_post_option_side_value hidden-option" value="0" <?php if(get_post_meta( $post->ID, "anb_location_post_option_side", true )== 'center') {echo 'style="display: none;"';} ?>>
					<?php
					} else {
					?>
						<input id="anb_location_post_option_side_value" name="anb_location_post_option_side_value" type="number" class="anb_location_post_option_side_value hidden-option" value="<?php echo get_post_meta( $post->ID, "anb_location_post_option_side_value", true ); ?>" <?php if(get_post_meta( $post->ID, "anb_location_post_option_side", true )== 'center') {echo 'style="display: none;"';} ?>>
					<?php
					}
					?>
					<select id="anb_location_post_option_side_unit" name="anb_location_post_option_side_unit" class="anb_location_post_option_side_unit hidden-option" <?php if(get_post_meta( $post->ID, "anb_location_post_option_side", true )== 'center') {echo 'style="display: none;"';} ?>>
						<?php
						$side_unit = get_post_meta( $post->ID, "anb_location_post_option_side_unit", true );
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
					<select name="anb_location_post_option_height" class="anb_location_post_option_height">
						<?php
						$height = get_post_meta( $post->ID, "anb_location_post_option_height", true );
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
					if ( get_post_meta( $post->ID, "anb_location_post_option_height_value", true ) == '' ) {
					?>
					<input name="anb_location_post_option_height_value" type="number" class="anb_location_post_option_height_value" value="100" >
					<?php
					} else {
					?>
					<input name="anb_location_post_option_height_value" type="number" class="anb_location_post_option_height_value" value="<?php echo get_post_meta( $post->ID, "anb_location_post_option_height_value", true ); ?>" >
					<?php
					}
					?>
					<select name="anb_location_post_option_height_unit" class="anb_location_post_option_height_unit">
						<?php
						$height_unit = get_post_meta( $post->ID, "anb_location_post_option_height_unit", true );
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
					<label for="z_index"><?php _e('Front order', 'alert-notice-boxes')?></label>
					<p><?php _e('', 'alert-notice-boxes')?></p>
				</th>
				<td>
					<input name="anb_location_post_option_z_index" type="number" class="anb_location_post_option_z_index" value="<?php echo get_post_meta( $post->ID, "anb_location_post_option_z_index", true ); ?>" min="1" >
				</td>
			</tr>
			</tbody>
		</table>
		</form>
	<?php
}

function save_anb_locations_item_meta_box( $post_id, $post) {
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;

	$slug = "anb_locations";
	if($slug != $post->post_type)
		return $post_id;

	update_post_meta($post_id, "anb_location_id", $post_id);
	$anb_location_post_option_width = $_POST["anb_location_post_option_width"];
	update_post_meta($post_id, "anb_location_post_option_width", $anb_location_post_option_width);
	$anb_location_post_option_width_unit = $_POST["anb_location_post_option_width_unit"];
	update_post_meta($post_id, "anb_location_post_option_width_unit", $anb_location_post_option_width_unit);
	$anb_location_post_option_side = $_POST["anb_location_post_option_side"];
	update_post_meta($post_id, "anb_location_post_option_side", $anb_location_post_option_side);
	$anb_location_post_option_side_value = $_POST["anb_location_post_option_side_value"];
	update_post_meta($post_id, "anb_location_post_option_side_value", $anb_location_post_option_side_value);
	$anb_location_post_option_side_unit = $_POST["anb_location_post_option_side_unit"];
	update_post_meta($post_id, "anb_location_post_option_side_unit", $anb_location_post_option_side_unit);
	$anb_location_post_option_height = $_POST["anb_location_post_option_height"];
	update_post_meta($post_id, "anb_location_post_option_height", $anb_location_post_option_height);
	$anb_location_post_option_height_value = $_POST["anb_location_post_option_height_value"];
	update_post_meta($post_id, "anb_location_post_option_height_value", $anb_location_post_option_height_value);
	$anb_location_post_option_height_unit = $_POST["anb_location_post_option_height_unit"];
	update_post_meta($post_id, "anb_location_post_option_height_unit", $anb_location_post_option_height_unit);
	$anb_location_post_option_z_index = $_POST["anb_location_post_option_z_index"];
	update_post_meta($post_id, "anb_location_post_option_z_index", $anb_location_post_option_z_index);



	$locations_style_css_file = fopen( YCANB_PLUGIN_DIR . "css/parts/locations_style.css", "w") or die("Unable to open file!");
	$posts_anb_locations = get_posts(array(
		'posts_per_page'=> -1,
		'post_type' => 'anb_locations',
	));

	$css_code = "\n";
	$css_code .= "\n";

	foreach ( $posts_anb_locations as $post_anb_location ) {
		$css_code .= '#anb-location-id-' . get_post_meta( $post_anb_location->ID, "anb_location_id", true ) . " {\n" ;
		$css_code .= "\t";
		$css_code .= "position: fixed;\n";
		$css_code .= "\t";
		$css_code .= "width: " . get_post_meta( $post_anb_location->ID, "anb_location_post_option_width", true ) . get_post_meta( $post_anb_location->ID, "anb_location_post_option_width_unit", true ) .";\n" ;
		if ( get_post_meta( $post_anb_location->ID, "anb_location_post_option_side", true ) == 'center' ) {
			$css_code .= "\t";
			$css_code .= "right: 50%;\n";
			$css_code .= "\t";
			$css_code .= "margin-right: -" . get_post_meta( $post_anb_location->ID, "anb_location_post_option_width", true ) / 2 . get_post_meta( $post_anb_location->ID, "anb_location_post_option_width_unit", true ) .";\n";
		} else {
			$css_code .= "\t";
			$css_code .= get_post_meta( $post_anb_location->ID, "anb_location_post_option_side", true ) . ': ' . get_post_meta( $post_anb_location->ID, "anb_location_post_option_side_value", true ) . get_post_meta( $post_anb_location->ID, "anb_location_post_option_side_unit", true ) .";\n" ;
		}
		$css_code .= "\t";
		$css_code .= get_post_meta( $post_anb_location->ID, "anb_location_post_option_height", true ) . ': ' . get_post_meta( $post_anb_location->ID, "anb_location_post_option_height_value", true ) . get_post_meta( $post_anb_location->ID, "anb_location_post_option_height_unit", true ) .";\n" ;
		$css_code .= "\t";
		$css_code .= "z-index: 99" . get_post_meta( $post_anb_location->ID, "anb_location_post_option_z_index", true ) .";\n" ;
		$css_code .= "}\n";
	}

	fwrite($locations_style_css_file, $css_code);
	fclose($locations_style_css_file);

	create_anb_css_stylesheet();
}

function duplicate_anb_locations_as_draft() {
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'anb_locations_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
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

function duplicate_anb_locations_link( $actions, $post ) {
	if ($post->post_type=='anb_locations' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=anb_locations_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="' . __('Duplicate this item', 'alert-notice-boxes') . '" rel="permalink">' . __('Duplicate', 'alert-notice-boxes') . '</a>';
		unset( $actions['view'] );
	}
	return $actions;
}

}

$YCanb_Locations = new YCanb_Locations;
