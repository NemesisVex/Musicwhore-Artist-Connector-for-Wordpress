<?php

/**
 * Musicwhore_Release
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Release')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');
	
	class Musicwhore_Release extends Musicwhore_Model {
		
		public $_table = 'mw_albums_releases';
		public $_primary_key = 'release_id';
		
		public function __construct() {
			parent::__construct();
			$this->load_relationship( array( 'model' => 'Musicwhore_Album', 'alias' => 'album') );
			$this->load_relationship( array( 'model' => 'Musicwhore_Release_Format', 'alias' => 'format' ) );
			$this->load_relationship( array( 'model' => 'Musicwhore_Album_Musicbrainz', 'alias' => 'musicbrainz' ) );
		}
		
		public function get($id, $args = null) {
			$release = parent::get($id, $args);
			if (!empty($release)) {
				$format = $this->format->get($release->release_format_id);
				$release->release_format_name = $format->format_name;
				$release->release_format_alias = $format->format_alias;
				$release->release_musicbrainz_id = $this->musicbrainz->get_many_by('mb_album_id', $id);
			}
			return $release;
		}
		
		public function get_album_releases($album_id) {
			$releases = $this->get_many_by('release_album_id', $album_id, $args);
			
			if (!empty($releases)) {
				$formats = $this->format->get_all();
				array_walk($releases, function ($release) use ($formats) {
					foreach ($formats as $format) {
						if ($format->format_id == $release->release_format_id) {
							$release->release_format_name = $format->format_name;
							$release->release_format_alias = $format->format_alias;
						}
					}
				});
			}
			
			return $releases;
		}
		
		public function get_release_from_amazon($asin, $country_name = 'United States') {
			
			$locale = array_search($country_name, Musicwhore_Artist_Connector_Aws::$_locale_labels);
			if (empty($locale)) {
				$locale = 'us';
			}
			
			$aws = new Musicwhore_Artist_Connector_Aws( array('locale' => $locale) );
			
			$parameters['ResponseGroup'] = 'Large';
			$wp_results = $aws->get($asin, $parameters);
			$aws_results = simplexml_load_string($wp_results['body']);
			
			if (!empty($aws_results->Request->Errors)) {
				throw new Exception($aws_results->Request->Errors->Error->Message);
			}
			
			return $aws_results->Items->Item;
		}
	}
}

