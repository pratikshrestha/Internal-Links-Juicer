<?php

class OILM_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles( $hook_suffix ) {
		if ( strpos( $hook_suffix, 'outpace-internal-link-manager' ) === false ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name, OILM_PLUGIN_URL . 'assets/admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts( $hook_suffix ) {
		if ( strpos( $hook_suffix, 'outpace-internal-link-manager' ) === false ) {
			return;
		}
		wp_enqueue_script( $this->plugin_name, OILM_PLUGIN_URL . 'assets/admin.js', array( 'jquery' ), $this->version, false );
	}

	public function add_plugin_admin_menu() {
		// Main Menu Page
		add_menu_page(
			__( 'Internal Links', 'outpace-internal-link-manager' ),
			__( 'Internal Links', 'outpace-internal-link-manager' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_rules_page' ),
			'dashicons-admin-links',
			30
		);

		// Submenus
		add_submenu_page(
			$this->plugin_name,
			__( 'Link Rules', 'outpace-internal-link-manager' ),
			__( 'Link Rules', 'outpace-internal-link-manager' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_rules_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Settings', 'outpace-internal-link-manager' ),
			__( 'Settings', 'outpace-internal-link-manager' ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'display_settings_page' )
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Reports', 'outpace-internal-link-manager' ),
			__( 'Reports', 'outpace-internal-link-manager' ),
			'manage_options',
			$this->plugin_name . '-reports',
			array( $this, 'display_reports_page' )
		);
	}

	public function display_rules_page() {
		require_once OILM_PLUGIN_DIR . 'includes/class-link-rules.php';
		$rules_page = new OILM_Link_Rules();
		$rules_page->render_page();
	}

	public function display_settings_page() {
		require_once OILM_PLUGIN_DIR . 'includes/class-settings.php';
		$settings_page = new OILM_Settings();
		$settings_page->render_page();
	}

	public function display_reports_page() {
		require_once OILM_PLUGIN_DIR . 'includes/class-reports.php';
		$reports_page = new OILM_Reports();
		$reports_page->render_page();
	}
}
