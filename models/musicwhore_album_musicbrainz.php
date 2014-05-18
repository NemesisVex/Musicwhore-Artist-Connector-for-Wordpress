<?php

/**
 * Musicwhore_Album_Musicbrainz
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Album_Musicbrainz')) {
	
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');
	
	class Musicwhore_Album_Musicbrainz extends Musicwhore_Model {
		
		public $_table = 'mw_albums_mb';
		public $_primary_key = 'mb_id';
		
		public function __construct() {
			parent::__construct();
		}
		
	}
}

