<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:10 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class AlbumMusicbrainz extends Base {

	public $_table = 'mw_albums_mb';
	public $_primary_key = 'mb_id';

	public function __construct() {
		parent::__construct();
	}

}