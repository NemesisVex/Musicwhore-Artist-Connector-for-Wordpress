<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 6/11/14
 * Time: 11:10 AM
 */

if (!class_exists('Musicwhore_Album_Meta')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_base_meta.php');

	class Musicwhore_Album_Meta extends Musicwhore_Base_Meta {

		public $_table = 'mw_albums_meta';

		public function __construct() {
			parent::__construct();
		}

		public function load($id) {
			parent::load('meta_album_id', $id);
		}

	}
}

