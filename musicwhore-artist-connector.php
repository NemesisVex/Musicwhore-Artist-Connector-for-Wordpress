<?php

/**
 * Plugin Name: Musicwhore.org Artist Connector
 * Plugin URI: http://archive.musicwhore.org
 * Description: This custom plugin connects the Musicwhore.org artist directory with content imported from Movable Type
 * Version: 0.01
 * Author: Greg Bueno
 * Author URI: http://vigilantmedia.com
 * License: MIT
 */
if (!class_exists('Musicwhore_Artist_Connector')) {

	class Musicwhore_Artist_Connector {
		
		private $settings;
		private $post_meta;
		private $rewrite;
		
		public function __construct() {
			// Setup settings.
			require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_settings.php');
			$this->settings = new Musicwhore_Artist_Connector_Settings();
			
			// Setup post meta data.
			require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_post_meta.php');
			$this->post_meta = new Musicwhore_Artist_Connector_Post_Meta();
			
			// Setup rewrite rules.
			require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_rewrite.php');
			$this->rewrite = new Musicwhore_Artist_Connector_Rewrite();
			
			add_action('init', array(&$this, 'init_js'));
			add_action('init', array(&$this, 'init_css'));
		}
		
		public function init_js() {
			wp_enqueue_script('chosen-js', plugin_dir_url(__FILE__) . 'js/chosen/chosen.jquery.min.js');
		}
		
		public function init_css() {
			wp_enqueue_style('chosen-css', plugin_dir_url(__FILE__) . 'js/chosen/chosen.min.css' );
		}
		
		public static function activate() {
			
		}

		public static function deactivate() {
			
		}
		
		public static function install() {
		}
	}

}

if (class_exists('Musicwhore_Artist_Connector')) {
	register_activation_hook(__FILE__, array('Musicwhore_Artist_Connector', 'activate'));
	register_deactivation_hook(__FILE__, array('Musicwhore_Artist_Connector', 'deactivate'));

	$mw_artist_connector = new Musicwhore_Artist_Connector();
	
	// Setup template tags.
	require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_template_functions.php');

	$mw_db_version = '0.01';
}
