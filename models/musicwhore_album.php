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

		public function __construct() {
			parent::__construct();
			$this->load_relationship( array( 'model' => 'Musicwhore_Artist', 'alias' => 'artist') );
			$this->load_relationship( array( 'model' => 'Musicwhore_Album_Format', 'alias' => 'format') );
			$this->load_relationship( array( 'model' => 'Musicwhore_Album_Meta', 'alias' => 'meta') );
		}
		
		public function get($id, $args = null) {
			$album = parent::get($id, $args);
			if (!empty($album)) {
				$album->album_format = $this->format->get($album->album_format_id);
			}
			return $album;
		}
		
		public function get_artist_albums($artist_id, $args = null) {
			$albums = $this->get_many_by('album_artist_id', $artist_id, $args);
			$formats = $this->format->get_all();
			$_this = $this;
			array_walk($albums, function ($album) use ($_this, $formats) {
				foreach ($formats as $format) {
					if ($album->album_format_id == $format->format_id) {
						$album->album_format = $format;
					}
				}
			});
			return $albums;
		}
	}
}

