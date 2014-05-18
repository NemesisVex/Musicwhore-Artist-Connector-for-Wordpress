<?php

/**
 * Musicwhore_Album
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Album')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');
	
	class Musicwhore_Album extends Musicwhore_Model {
		
		public $_table = 'mw_albums';
		public $_primary_key = 'album_id';
		private $_format_masks = array(
			2 => 'album', 
			4 => 'single',
			8 => 'ep',
			32 => 'video',
			64 => 'book',
		);
		
		public function __construct() {
			parent::__construct();
			$this->load_relationship( array( 'model' => 'Musicwhore_Artist', 'alias' => 'artist') );
		}
		
		public function get($id, $args = null) {
			$album = parent::get($id, $args);
			if (!empty($album)) {
				$album->album_format = $this->parse_format_mask($album->album_format_mask);
			}
			return $album;
		}
		
		public function get_artist_albums($artist_id, $args = null) {
			$albums = $this->get_many_by('album_artist_id', $artist_id, $args);
			$_this = $this;
			array_walk($albums, function ($album) use ($_this) {
				$album->album_format = $_this->parse_format_mask($album->album_format_mask);
			});
			return $albums;
		}
		
		public function parse_format_mask($format_mask) {
			return $this->_format_masks[$format_mask];
		}
	}
}

