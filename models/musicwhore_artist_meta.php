<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 6/11/14
 * Time: 4:47 PM
 */

if (!class_exists('Musicwhore_Artist_Meta')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_base_meta.php');

	class Musicwhore_Artist_Meta extends Musicwhore_Base_Meta {

		public $_table = 'mw_artists_meta';

		public function __construct() {
			parent::__construct();
		}

		public function load($id) {
			parent::load('meta_artist_id', $id);
		}

	}
}
