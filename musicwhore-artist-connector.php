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

// Create an autoloader for classes.
if (!function_exists( __NAMESPACE__ . '\\autoload' )) {
	function autoload( $class_name )
	{
		$class_name = ltrim($class_name, '\\');
		if ( strpos( $class_name, __NAMESPACE__ ) !== 0 ) {
			return;
		}

		$class_name = str_replace( __NAMESPACE__, '', $class_name );

		$path = plugin_dir_path(__FILE__) . '/lib' . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

		require_once( $path );
	}
}

spl_autoload_register (__NAMESPACE__ . '\\autoload' );

// Run any activation hooks.
register_activation_hook( __FILE__, array( 'ObservantRecords\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Setup', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ObservantRecords\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Setup', 'deactivate' ) );

// Run any setup needed for the plugin.
Setup::init();

// Creating the settings page for the plugin.
Settings::init();

// Add meta data fields in posts.
PostMeta::init();

// Create additional rewrite rules.
Rewrite::init();

// Setup template tags.
require_once( plugin_dir_path( __FILE__ ) . '/musicwhore-artist-connector-template-functions.php' );
