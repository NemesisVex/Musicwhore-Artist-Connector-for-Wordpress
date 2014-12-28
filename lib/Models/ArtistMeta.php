<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 12/26/14
 * Time: 11:45 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;


class ArtistMeta extends Meta {

	public $_table = 'mw_artists_meta';

	public function __construct() {
		parent::__construct();
	}

	public function load($id) {
		parent::load('meta_artist_id', $id);
	}

}