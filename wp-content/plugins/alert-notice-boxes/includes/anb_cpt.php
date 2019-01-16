<?php
function register_anb_cpt() {
    register_post_type( 'anb', array(
        'labels' => array(
		'name'               => __( 'Alert Notice', 'alert-notice-boxes' ),
		'singular_name'      => _x( 'Alert Notice', 'post type singular name', 'alert-notice-boxes' ),
		'menu_name'          => _x( 'Alert Notice', 'admin menu', 'alert-notice-boxes' ),
		'name_admin_bar'     => _x( 'Alert Notice', 'add new on admin bar', 'alert-notice-boxes' ),
		'add_new'            => _x( 'Add New', 'Post Type', 'alert-notice-boxes' ),
		'add_new_item'       => __( 'Add New', 'alert-notice-boxes' ),
		'new_item'           => __( 'New Alert Notice Box', 'alert-notice-boxes' ),
		'edit_item'          => __( 'Edit Alert Notice Box', 'alert-notice-boxes' ),
		'view_item'          => __( 'View Alert Notice Box', 'alert-notice-boxes' ),
		'all_items'          => __( 'Alert Notice', 'alert-notice-boxes' ),
		'search_items'       => __( 'Search', 'alert-notice-boxes' ),
		'parent_item_colon'  => __( 'Parent Alert Notice Box:', 'alert-notice-boxes' ),
		'not_found'          => __( 'No Alert Notice Box found.', 'alert-notice-boxes' ),
		'not_found_in_trash' => __( 'No Alert Notice Box found in Trash.', 'alert-notice-boxes' ),
		),

		// Frontend // Admin
		'supports'              => array( 'title', 'editor' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 100,
		'menu_icon'             => 'dashicons-megaphone',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'anb',
		'map_meta_cap'          => true
    ) );
}
add_action( 'init', 'register_anb_cpt', 0 );

function anb_capabilities() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'delete_anbs', true );
	$role->add_cap( 'delete_others_anbs', true );
	$role->add_cap( 'delete_private_anbs', true );
	$role->add_cap( 'delete_published_anbs', true );
	$role->add_cap( 'edit_anbs', true );
	$role->add_cap( 'edit_others_anbs', true );
	$role->add_cap( 'edit_private_anbs', true );
	$role->add_cap( 'edit_published_anbs', true );
	$role->add_cap( 'publish_anbs', true );
	$role->add_cap( 'read_private_anbs', true );
}
add_action( 'admin_init', 'anb_capabilities' );

function anb_deactivation() {
	$role = get_role( 'administrator' );
	$role->remove_cap( 'delete_anbs');
	$role->remove_cap( 'delete_others_anbs');
	$role->remove_cap( 'delete_private_anbs');
	$role->remove_cap( 'delete_published_anbs');
	$role->remove_cap( 'edit_anbs');
	$role->remove_cap( 'edit_others_anbs');
	$role->remove_cap( 'edit_private_anbs');
	$role->remove_cap( 'edit_published_anbs');
	$role->remove_cap( 'publish_anbs');
	$role->remove_cap( 'read_private_anbs');
}
register_deactivation_hook( YCANB_PLUGIN_URL, 'anb_deactivation' );
