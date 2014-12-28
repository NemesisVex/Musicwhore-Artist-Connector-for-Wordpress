<?php

/**
 * Plugin Name: Musicwhore.org Artist Connector
 * Plugin URI: https://bitbucket.org/NemesisVex/musicwhore-artist-connector-for-wordpress
 * Description: This custom plugin connects the Musicwhore.org artist directory with content imported from Movable Type
 * Version: 2.0
 * Author: Greg Bueno
 * Author URI: http://vigilantmedia.com
 * License: MIT
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;


/*
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

			// Load JavaScript and CSS dependencies.
			add_action('init', array(&$this, 'init_js'));
			add_action('init', array(&$this, 'init_css'));
		}

		public function init_js() {
			wp_enqueue_script('chosen-js', plugin_dir_url(__FILE__) . 'js/chosen/chosen.jquery.min.js');
		}
		
		public function init_css() {
			wp_enqueue_style('chosen-css', plugin_dir_url(__FILE__) . 'js/chosen/chosen.min.css' );
			wp_enqueue_style('mw-meta-css', plugin_dir_url(__FILE__) . 'css/layout.css' );
		}
		
		public static function activate() {
			delete_option('aws_secret_key');
		}

		public static function deactivate() {
			
		}
		
		public static function install() {

		}
	}

}

// The actual execution of the plugin code happens here.
if (class_exists('Musicwhore_Artist_Connector')) {
	// Fire activiation hooks.
	register_activation_hook(__FILE__, array('Musicwhore_Artist_Connector', 'activate'));
	register_deactivation_hook(__FILE__, array('Musicwhore_Artist_Connector', 'deactivate'));

	// Instantiating an instance of the Musicwhore Artist Connector fires all necessary WordPress hooks.
	$mw_artist_connector = new Musicwhore_Artist_Connector();
	
	// Setup template tags.
	require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_template_functions.php');

	$mw_db_version = '1.1.1';
}
*/

if (!function_exists( __NAMESPACE__ . '\\autoload' )) {
	function autoload( $class_name )
	{
		$class_name = ltrim($class_name, '\\');
		if ( strpos( $class_name, __NAMESPACE__ ) !== 0 ) {
			return;
		}

		$class_name = str_replace( __NAMESPACE__, '', $class_name );

		$path = plugin_dir_path(__FILE__) . '/lib' . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

		require_once($path);
	}
}

spl_autoload_register(__NAMESPACE__ . '\\autoload');

register_activation_hook(__FILE__, array('ObservantRecords\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Setup', 'activate'));
register_deactivation_hook(__FILE__, array('ObservantRecords\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Setup', 'deactivate'));

Setup::init();
Settings::init();
