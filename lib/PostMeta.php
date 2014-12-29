<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 12/26/14
 * Time: 11:50 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;


use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Artist;
use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Driver;

class PostMeta {

	private $mw_db;

	public function __construct() {
		// Connect to artist database.
		$driver = new Driver();
		$this->mw_db = $driver->getDriver();
	}

	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'addMetaBoxes' ) );
		add_action( 'save_post', array( __CLASS__, 'savePostMeta' ) );
		add_action( 'wp_ajax_get_artist_albums', array( __CLASS__, 'get_artist_albums' ) );
		add_action( 'wp_ajax_get_album_releases', array( __CLASS__, 'get_album_releases' ) );
	}

	public static function addMetaBoxes() {
		add_meta_box( 'meta_mw_artist_id', 'Musicwhore Metadata', array( __CLASS__, 'renderMetaBox' ), 'post', 'normal', 'high' );
	}

	public function renderMetaBox ( $post ) {
		if (!current_user_can('edit_posts')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}

		$mw_artist_id = get_post_meta( $post->ID, '_mw_artist_id', true );
		$mw_album_id = get_post_meta( $post->ID, '_mw_album_id', true );
		$mw_release_id = get_post_meta( $post->ID, '_mw_release_id', true );

		$artist_model = new Artist();
		if ( $artist_model->getDriverStatus() === true ) {
			$artists = $artist_model->getArtists();
		}

		return;

		if (!empty($mw_artist_id)) {
			$album_model = new Musicwhore_Album();
			if ( $album_model->get_driver_status() === true ) {
				$albums = $album_model->get_artist_albums($mw_artist_id);
				usort($albums, function ($a, $b) {
					return ($a->album_title == $b->album_title) ? 0 : ( $a->album_title < $b->album_title ? -1 : 1 );
				});
			}
		}

		if (!empty($mw_album_id)) {
			$release_model = new Musicwhore_Release();
			if ( $release_model->get_driver_status() === true ) {
				$releases = $release_model->get_album_releases($mw_album_id);
				usort($releases, function ($a, $b) {
					return ($a->release_catatalog_num == $b->release_catatalog_num) ? 0 : ( $a->release_catatalog_num < $b->release_catatalog_num ? -1 : 1 );
				});
			}
		}

		include(sprintf("%s/templates/mw_meta_box.php", dirname(__FILE__)));
	}

	public static function get_artist_albums() {
		$mw_artist_id = $_POST['mw_artist_id'];

		if (!empty( $mw_artist_id )) {
			$album_model = new Musicwhore_Album();
			if ( $album_model->get_driver_status() === true ) {
				$albums = $album_model->get_artist_albums($mw_artist_id);
				usort($albums, function ($a, $b) {
					return ($a->album_title == $b->album_title) ? 0 : ( $a->album_title < $b->album_title ? -1 : 1 );
				});
			}
			$albums_json = json_encode( $albums );
			echo $albums_json;
		}
		die();
	}


	public static function get_album_releases() {
		$mw_album_id = $_POST['mw_album_id'];

		if (!empty( $mw_album_id )) {
			$release_model = new Musicwhore_Release();
			if ( $release_model->get_driver_status() === true ) {
				$releases = $release_model->get_album_releases($mw_album_id);
				usort($releases, function ($a, $b) {
					return ($a->release_catatalog_num == $b->release_catatalog_num) ? 0 : ( $a->release_catatalog_num < $b->release_catatalog_num ? -1 : 1 );
				});
			}
			$releases_json = json_encode( $releases );
			echo $releases_json;
		}
		die();
	}


	public function savePostMeta( $post_id ) {
		$mw_artist_id = $_POST['mw_artist_id'];
		$mw_album_id = $_POST['mw_album_id'];
		$mw_release_id = $_POST['mw_release_id'];

		(empty($mw_artist_id)) ? delete_post_meta($post_id, '_mw_artist_id') : update_post_meta($post_id, '_mw_artist_id', $mw_artist_id);
		(empty($mw_album_id)) ? delete_post_meta($post_id, '_mw_album_id') : update_post_meta($post_id, '_mw_album_id', $mw_album_id);
		(empty($mw_release_id)) ? delete_post_meta($post_id, '_mw_release_id') : update_post_meta($post_id, '_mw_release_id', $mw_release_id);
	}

}