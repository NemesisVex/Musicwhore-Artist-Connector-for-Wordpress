<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:01 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class AlbumFormat extends Base {

	public $_table = 'mw_albums_formats';
	public $_primary_key = 'format_id';

	public function __construct() {
		parent::__construct();
	}

}