<?php

class OILM_Activator {

	public static function activate() {
		self::create_tables();
		self::set_default_options();
	}

	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$rules_table_name = $wpdb->prefix . 'oilm_rules';

		$sql = "CREATE TABLE $rules_table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			keywords text NOT NULL,
			url varchar(255) NOT NULL,
			title_attr varchar(255) DEFAULT '' NOT NULL,
			is_exact_match tinyint(1) DEFAULT 0 NOT NULL,
			open_new_tab tinyint(1) DEFAULT 0 NOT NULL,
			is_nofollow tinyint(1) DEFAULT 0 NOT NULL,
			is_sponsored tinyint(1) DEFAULT 0 NOT NULL,
			max_links_per_page int(11) DEFAULT 0 NOT NULL,
			max_uses_per_keyword int(11) DEFAULT 0 NOT NULL,
			is_active tinyint(1) DEFAULT 1 NOT NULL,
			priority int(11) DEFAULT 10 NOT NULL,
			insert_count bigint(20) DEFAULT 0 NOT NULL,
			last_inserted_at datetime DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'oilm_db_version', OILM_VERSION );
	}

	private static function set_default_options() {
		$default_settings = array(
			'global_max_links' => 0,
			'global_max_url_links' => 0,
			'enable_plugin' => 1,
			'enabled_post_types' => array('post', 'page'),
			'enable_elementor' => 1,
			'exclude_headings' => 1,
			'exclude_existing_links' => 1,
			'default_new_tab' => 0,
			'default_nofollow' => 0,
			'debug_mode' => 0,
			'remove_data_on_uninstall' => 0,
		);

		add_option( 'oilm_settings', $default_settings );
	}

}
