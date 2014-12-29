<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:10 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class ReleaseMeta extends Meta {

	public $_table = 'mw_albums_releases_meta';

	public function __construct() {
		parent::__construct();
	}

	public function load($id) {
		parent::load( 'meta_release_id', $id );
	}

}