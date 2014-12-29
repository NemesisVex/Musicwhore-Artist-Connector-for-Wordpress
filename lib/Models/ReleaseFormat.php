<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:09 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class ReleaseFormat extends Base {

	public $_table = 'mw_albums_releases_formats';
	public $_primary_key = 'format_id';

	public function __construct() {
		parent::__construct();
	}

}