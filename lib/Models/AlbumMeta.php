<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:01 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class AlbumMeta extends Meta {

	public $_table = 'mw_albums_meta';

	public function __construct() {
		parent::__construct();
	}

	public function load($id) {
		parent::load('meta_album_id', $id);
	}

}