<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/30/2014
 * Time: 8:26 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class TrackMeta extends Meta {

	public $_table = 'mw_albums_tracks_meta';

	public function __construct() {
		parent::__construct();
	}

	public function load( $id ) {
		parent::load( 'meta_track_id', $id );
	}

}