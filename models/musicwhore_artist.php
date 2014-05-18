<?php

/**
 * Musicwhore_Artist
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');
	
	class Musicwhore_Artist extends Musicwhore_Model {
		
		public $_table = 'mw_artists';
		public $_primary_key = 'artist_id';
		
		public function __construct() {
			parent::__construct();
			$this->load_relationship( array( 'model' => 'Musicwhore_Artist_Personnel', 'alias' => 'members' ) );
		}
		
		public function get($id, $args = null) {
			$artist = parent::get($id, $args);
			if (!empty($artist)) {
				$artist->artist_display_name = $this->format_artist_name($artist);
				$artist->artist_members = $this->members->get_artist_members($id);
			}
			return $artist;
		}
		
		public function get_artists($filter = null) {
			if (!empty($filter)) {
				$artists = $this->get_many_like('artist_last_name', $filter, 'after', array( 'order_by' => 'artist_last_name' ));
			} else {
				$artists = $this->get_all( array( 'order_by' => 'artist_last_name' ) );
			}
			
			$_this = $this;
			array_walk($artists, function ($artist) use ($_this) {
				$artist->artist_display_name = $_this->format_artist_name($artist);
			});
			
			return $artists;
		}
		
		public function get_artists_nav() {
			$nav = $this->mw_db->get_results( 'select upper(substring(artist_last_name from 1 for 1)) as nav from mw_artists group by nav order By nav' );
			return $nav;
		}
		
		public function format_artist_name($artist) {
			$artist_display_name = null;
			if (empty($artist->artist_first_name)) {
				$artist_display_name = $artist->artist_last_name;
			} else {
				$artist_display_name = (($artist->artist_settings_mask & 2) == 2) ? $artist->artist_last_name . ' '  .$artist->artist_first_name : $artist->artist_first_name . ' ' . $artist->artist_last_name;
			}
			
			return $artist_display_name;
		}
		
	}
}

