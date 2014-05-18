<?php

/**
 * Musicwhore_Track
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Track')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');
	require_once(plugin_dir_path(__FILE__) . '../musicwhore_artist_connector_aws.php');
	
	class Musicwhore_Track extends Musicwhore_Model {
		
		public $_table = 'mw_albums_tracks';
		public $_primary_key = 'track_id';
		
		public function __construct() {
			parent::__construct();
			$this->load_relationship( array( 'model' => 'Musicwhore_Release', 'alias' => 'release' ) );
		}
		
		public function get_release_tracks($release_id, $args = null) {
			if (empty($args['order_by'])) {
				$args['order_by'] = 'track_disc_num, track_track_num';
			}
			$tracks = $this->get_many_by('track_release_id', $release_id, $args);
			return $tracks;
		}
		
		public function get_amazon_tracks($asin, $country_name = 'United States') {
			
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
			
			$aws_tracks = $aws_results->Items->Item->Tracks;
			$tracks = Musicwhore_Track::parse_aws_tracks($aws_tracks);
			
			return $tracks;
		}
		
		public static function parse_aws_tracks($aws_tracks) {
			$tracks = array();
			
			foreach ($aws_tracks->Disc as $aws_disc) {
				$disc_num = (int) $aws_disc->attributes()->Number;
				$track = array();
				$t = 1;
				foreach ($aws_disc->Track as $aws_track) {
					$track['track_disc_num'] = $disc_num;
					$track['track_track_num'] = $t;
					$track['track_song_title'] = (string) $aws_track;
					$t++;
					
					$tracks[] = (object) $track;
				}
			}
			
			return $tracks;
		}
	}
}

