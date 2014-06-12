<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 6/11/14
 * Time: 8:12 PM
 */

if (!class_exists('Musicwhore_Track_Meta')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_base_meta.php');

	class Musicwhore_Track_Meta extends Musicwhore_Base_Meta {

		public $_table = 'mw_albums_tracks_meta';

		public function __construct() {
			parent::__construct();
		}

		public function load($id) {
			parent::load('meta_track_id', $id);
		}


	}
}

