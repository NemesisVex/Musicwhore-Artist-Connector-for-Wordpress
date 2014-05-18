<?php

/**
 * Musicwhore_Artist_Personnel
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Personnel')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');

	class Musicwhore_Artist_Personnel extends Musicwhore_Model {

		public $_table = 'mw_artists_personell';
		public $_primary_key = 'member_id';

		public function __construct() {
			parent::__construct();
		}

		public function get_artist_members($id, $args = null) {
			$members = $this->get_many_by('member_artist_id', $id, $args);
			return $members;
		}

	}
	
}

