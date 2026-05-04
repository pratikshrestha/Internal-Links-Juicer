<?php

class OILM_Deactivator {

	public static function deactivate() {
		// Clear any transients or cached data
		delete_transient( 'oilm_active_rules' );
	}

}
