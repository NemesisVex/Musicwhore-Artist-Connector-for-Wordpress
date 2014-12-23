<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/23/2014
 * Time: 2:53 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;

const WP_PLUGIN_DOMAIN = 'musicwhore_artist_connector';

class Setup {

	public function __construct() {

	}

	public static function init() {

	}

	public static function activate() {
		delete_option('aws_secret_key');
	}

	public static function deactivate() {

	}

	public static function install() {

	}
}