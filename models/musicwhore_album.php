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

				$this->meta->load($id);
				$album->settings = $this->meta->get_settings();
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

		public function get_classical_albums($artist_id, $args = null) {
			$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

			$results = (object)array();

			$soloist_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'soloist_id' and meta_field_value = %d", $artist_id);
			$results->soloist = $this->mw_db->get_results($soloist_query);

			$ensemble_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'ensemble_id' and meta_field_value = %d", $artist_id);
			$results->ensemble = $this->mw_db->get_results($ensemble_query);

			$conductor_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'conductor_id' and meta_field_value = %d", $artist_id);
			$results->conductor = $this->mw_db->get_results($conductor_query);

			$formats = $this->format->get_all();
			$_this = $this;

			foreach ($results as $albums) {
				array_walk($albums, function ($album) use ($_this, $formats) {
					foreach ($formats as $format) {
						if ($album->album_format_id == $format->format_id) {
							$album->album_format = $format;
						}
					}
				});
			}

			return $results;
		}
	}
}

