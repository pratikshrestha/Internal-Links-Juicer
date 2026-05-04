<?php

class OILM_Settings {

	public function register_settings() {
		register_setting( 'oilm_settings_group', 'oilm_settings' );

		add_settings_section(
			'oilm_general_settings',
			__( 'General Settings', 'internal-link-manager' ),
			array( $this, 'general_settings_cb' ),
			'oilm_settings_page'
		);

		// Global toggle
		add_settings_field( 'enable_plugin', __( 'Enable Plugin', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'enable_plugin' ) );
		
		// Post types
		add_settings_field( 'enabled_post_types', __( 'Enabled Post Types', 'internal-link-manager' ), array( $this, 'render_post_types' ), 'oilm_settings_page', 'oilm_general_settings' );

		// Limits
		add_settings_field( 'global_max_links', __( 'Global Max Links per Page', 'internal-link-manager' ), array( $this, 'render_number' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'global_max_links', 'desc' => '0 for unlimited' ) );
		add_settings_field( 'global_max_url_links', __( 'Global Max Links per Target URL per Page', 'internal-link-manager' ), array( $this, 'render_number' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'global_max_url_links', 'desc' => '0 for unlimited' ) );

		// Exclusions
		add_settings_field( 'exclude_headings', __( 'Exclude Headings', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'exclude_headings', 'desc' => 'Do not add links inside H1-H6 tags' ) );
		add_settings_field( 'exclude_existing_links', __( 'Exclude Existing Links', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'exclude_existing_links', 'desc' => 'Do not add links inside existing anchor tags' ) );
		
		// Defaults
		add_settings_field( 'default_new_tab', __( 'Open links in new tab default', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'default_new_tab' ) );
		add_settings_field( 'default_nofollow', __( 'Add nofollow default', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'default_nofollow' ) );

		// Compat
		add_settings_field( 'enable_elementor', __( 'Enable Elementor Compatibility', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'enable_elementor' ) );
		
		// System
		add_settings_field( 'debug_mode', __( 'Debug Mode', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'debug_mode' ) );
		add_settings_field( 'remove_data_on_uninstall', __( 'Remove Data on Uninstall', 'internal-link-manager' ), array( $this, 'render_checkbox' ), 'oilm_settings_page', 'oilm_general_settings', array( 'id' => 'remove_data_on_uninstall', 'desc' => 'Delete all rules and settings if the plugin is uninstalled.' ) );
	}

	public function general_settings_cb() {
		echo '<p>' . __( 'Configure the global settings for internal linking.', 'internal-link-manager' ) . '</p>';
	}

	public function render_checkbox( $args ) {
		$options = get_option( 'oilm_settings' );
		$id = $args['id'];
		$checked = isset( $options[$id] ) ? $options[$id] : 0;
		echo '<input type="checkbox" name="oilm_settings[' . $id . ']" value="1" ' . checked( 1, $checked, false ) . ' />';
		if ( isset( $args['desc'] ) ) {
			echo '<p class="description">' . esc_html( $args['desc'] ) . '</p>';
		}
	}

	public function render_number( $args ) {
		$options = get_option( 'oilm_settings' );
		$id = $args['id'];
		$val = isset( $options[$id] ) ? absint($options[$id]) : 0;
		echo '<input type="number" name="oilm_settings[' . $id . ']" value="' . esc_attr($val) . '" min="0" />';
		if ( isset( $args['desc'] ) ) {
			echo '<p class="description">' . esc_html( $args['desc'] ) . '</p>';
		}
	}

	public function render_post_types() {
		$options = get_option( 'oilm_settings' );
		$enabled = isset( $options['enabled_post_types'] ) && is_array( $options['enabled_post_types'] ) ? $options['enabled_post_types'] : array('post', 'page');
		
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		
		foreach ( $post_types as $pt ) {
			if ( $pt->name === 'attachment' ) continue;
			$checked = in_array( $pt->name, $enabled ) ? 'checked="checked"' : '';
			echo '<label style="margin-right: 15px;">';
			echo '<input type="checkbox" name="oilm_settings[enabled_post_types][]" value="' . esc_attr( $pt->name ) . '" ' . $checked . ' /> ';
			echo esc_html( $pt->label );
			echo '</label>';
		}
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'oilm_settings_group' );
				do_settings_sections( 'oilm_settings_page' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
