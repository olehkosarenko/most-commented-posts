<?php

/**
 * The file that defines the core plugin class
 *
 */

if( ! class_exists('Most_Commented_Posts') ):

class Most_Commented_Posts {

	function __construct() {
		// None
	}

	/**
	 * Run core of the plugin
	 */
	function run(){
		$this->load();
		add_action( 'plugins_loaded', array( $this, 'set_locale' ) );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	function set_locale() {
		load_plugin_textdomain('mostcp', false, dirname( plugin_basename( MOSTCP_FILE ) ) . '/languages/');
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	function load() {
		require_once MOSTCP_INC_PATH . 'class-most-commented-posts-data.php';
		require_once MOSTCP_INC_PATH . 'class-most-commented-posts-public.php';
		$public = new Most_Commented_Posts_Public();
		if( is_admin() ){
			require_once MOSTCP_INC_PATH . 'class-most-commented-posts-admin.php';
			$admin = new Most_Commented_Posts_Admin();
		}
	}

}

endif;