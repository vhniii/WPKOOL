<?php
global $anb_version;
$anb_version = '2.0.9'; // version changed from 1.0.0 to 1.0.0

function alert_notice_boxes_install() {
	global $wpdb;
	global $anb_version;
	$table_name = $wpdb->prefix . 'alert_notice_boxes'; // do not forget about tables prefix
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		post_ID int(11) NOT NULL,
		title TEXT NULL,
		display_in TEXT NULL,
		location_id VARCHAR(100) NULL,
		design_id VARCHAR(100) NULL,
		animation_id VARCHAR(100) NULL,
		animation_out_id VARCHAR(100) NULL,
		delay int(11) DEFAULT '2000',
		show_time int(11) DEFAULT '8000',
		enabled VARCHAR(100) NULL,
		user_types VARCHAR(100) NULL,
		device_class VARCHAR(100) NULL,
		PRIMARY KEY  (id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option('anb_version', $anb_version);
	}

	$installed_ver = get_option('anb_version');
	if ($installed_ver != $anb_version) {
		$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		post_ID int(11) NOT NULL,
		title TEXT NULL,
		display_in TEXT NULL,
		location_id VARCHAR(100) NULL,
		design_id VARCHAR(100) NULL,
		animation_id VARCHAR(100) NULL,
		animation_out_id VARCHAR(100) NULL,
		delay int(11) DEFAULT '2000',
		show_time int(11) DEFAULT '8000',
		enabled VARCHAR(100) NULL,
		user_types VARCHAR(100) NULL,
		device_class VARCHAR(100) NULL,
		PRIMARY KEY  (id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		update_option('anb_version', $anb_version);

		if($wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE 'only_logged_users'") == 'only_logged_users') {
			$wpdb->query( "ALTER TABLE $table_name DROP COLUMN only_logged_users" );
		}

		if (version_compare($installed_ver, '2.0.0', '<')) {
		    fix_old_ver();
		}

    }

}

register_activation_hook(__FILE__, 'alert_notice_boxes_install');

function anb_update_db_check() {
    global $anb_version;
    if (get_site_option('anb_version') != $anb_version) {
        alert_notice_boxes_install();
    }
}

add_action('plugins_loaded', 'anb_update_db_check');
