<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 6/11/14
 * Time: 11:21 AM
 */

if (!class_exists('Musicwhore_Release_Meta')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_base_meta.php');

	class Musicwhore_Release_Meta extends Musicwhore_Base_Meta {

		public $_table = 'mw_albums_releases_meta';

		public function __construct() {
			parent::__construct();
		}

		public function load($id) {
			parent::load('meta_release_id', $id);
		}

	}
}

