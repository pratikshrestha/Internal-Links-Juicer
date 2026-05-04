<?php

class OILM_WooCommerce_Compat {

	private $processor;

	public function __construct( $processor ) {
		$this->processor = $processor;
	}

	public function init() {
		// Only apply if WooCommerce is active
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = get_option('oilm_settings');
		$enabled_types = isset($settings['enabled_post_types']) ? $settings['enabled_post_types'] : array();

		// Apply if product post type is enabled
		if ( in_array( 'product', $enabled_types ) ) {
			add_filter( 'woocommerce_short_description', array( $this->processor, 'process_content' ), 99 );
		}
	}
}
