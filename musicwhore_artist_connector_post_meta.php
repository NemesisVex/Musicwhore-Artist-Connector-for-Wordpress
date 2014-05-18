<?php

/**
 * Musicwhore_Artist_Connector_Post_Meta
 *
 * Musicwhore_Artist_Connector_Post_Meta contains hooks to render
 * post meta data fields in the edit screen for posts.
 * 
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Connector_Post_Meta')) {
	
	require(plugin_dir_path(__FILE__) . 'models/musicwhore_artist.php');
	require(plugin_dir_path(__FILE__) . 'models/musicwhore_album.php');
	require(plugin_dir_path(__FILE__) . 'models/musicwhore_release.php');
	require(plugin_dir_path(__FILE__) . 'models/musicwhore_track.php');
	
	class Musicwhore_Artist_Connector_Post_Meta {
		
		private $mw_db;
		
		public function __construct() {
			// Connect to artist database.
			require_once(plugin_dir_path(__FILE__) . '/musicwhore_artist_connector_db_driver.php');
			$db_driver = new Musicwhore_Artist_Connector_Db_Driver();
			$this->mw_db = $db_driver->get_driver();
			
			// Setup post meta
			add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
			add_action('save_post', array(&$this, 'save_post_meta'));
		}
		
		public function add_meta_boxes() {
			add_meta_box('meta_mw_artist_id', 'Musicwhore Metadata', array(&$this, 'render_mw_meta_box'), 'post', 'normal', 'high');
		}
		
		public function render_mw_meta_box ( $post ) {			
			if (!current_user_can('edit_posts')) {
				wp_die('You do not have sufficient permissions to access this page.');
			}
			
			$mw_artist_id = get_post_meta($post->ID, '_mw_artist_id', true);
			$mw_album_id = get_post_meta($post->ID, '_mw_album_id', true);
			$mw_release_id = get_post_meta($post->ID, '_mw_release_id', true);
			
			$artist_model = new Musicwhore_Artist();
			$artists = $artist_model->get_artists();
			
			if (!empty($mw_artist_id)) {
				$album_model = new Musicwhore_Album();
				$albums = $album_model->get_artist_albums($mw_artist_id);
				usort($albums, function ($a, $b) {
					return ($a->album_title == $b->album_title) ? 0 : ( $a->album_title < $b->album_title ? -1 : 1 );
				});
			}
			
			if (!empty($mw_album_id)) {
				$release_model = new Musicwhore_Release();
				$releases = $release_model->get_album_releases($mw_album_id);
				usort($albums, function ($a, $b) {
					return ($a->album_catalog_num == $b->album_catalog_num) ? 0 : ( $a->album_catalog_num < $b->album_catalog_num ? -1 : 1 );
				});
			}

			include(sprintf("%s/templates/mw_meta_box.php", dirname(__FILE__)));
		}
		
		public function save_post_meta( $post_id ) {
			$mw_artist_id = $_POST['mw_artist_id'];
			$mw_album_id = $_POST['mw_album_id'];
			$mw_release_id = $_POST['mw_release_id'];
			
			(empty($mw_artist_id)) ? delete_post_meta($post_id, '_mw_artist_id') : update_post_meta($post_id, '_mw_artist_id', $mw_artist_id);
			(empty($mw_album_id)) ? delete_post_meta($post_id, '_mw_album_id') : update_post_meta($post_id, '_mw_album_id', $mw_album_id);
			(empty($mw_release_id)) ? delete_post_meta($post_id, '_mw_release_id') : update_post_meta($post_id, '_mw_release_id', $mw_release_id);
		}
	}
}

