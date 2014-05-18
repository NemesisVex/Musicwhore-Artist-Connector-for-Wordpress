<?php

/**
 * Musicwhore_Artist_Connector_Template_Functions
 *
 * @author Greg Bueno
 */

require_once(plugin_dir_path(__FILE__) . 'models/musicwhore_artist.php');
require_once(plugin_dir_path(__FILE__) . 'models/musicwhore_album.php');
require_once(plugin_dir_path(__FILE__) . 'models/musicwhore_release.php');
require_once(plugin_dir_path(__FILE__) . 'models/musicwhore_track.php');
require_once(plugin_dir_path(__FILE__) . 'musicwhore_artist_connector_aws.php');

if (!function_exists('get_all_artists')) {
	function get_all_artists($filter = null) {
		$model = new Musicwhore_Artist();
		$artists = $model->get_artists($filter);
		return $artists;
	}
}

if (!function_exists('get_artist')) {
	function get_artist($artist_id) {
		$model = new Musicwhore_Artist();
		$artist = $model->get($artist_id);
		$artist->albums = get_artist_albums($artist_id);
		return $artist;
	}
}

if (!function_exists('get_artists_nav')) {
	function get_artists_nav() {
		$model = new Musicwhore_Artist();
		return $model->get_artists_nav();
	}
}

if (!function_exists('get_artist_albums')) {
	function get_artist_albums($artist_id, $args = null) {
		$model = new Musicwhore_Album();
		$albums = $model->get_artist_albums($artist_id, $args);
		return $albums;
	}
}

if (!function_exists('get_album')) {
	function get_album($album_id) {
		$model = new Musicwhore_Album();
		$album = $model->get($album_id);
		$album->artist = $model->artist->get($album->album_artist_id);
		$album->releases = get_album_releases($album_id);
		return $album;
	}
}

if (!function_exists('get_album_releases')) {
	function get_album_releases($album_id, $args = null) {
		$release = new Musicwhore_Release();
		return $release->get_album_releases($album_id, $args);
	}
}

if (!function_exists('get_release')) {
	function get_release($release_id) {
		$model = new Musicwhore_Release();
		$release = $model->get($release_id);
		$release->album = $model->album->get($release->release_album_id);
		$release->album->artist = $model->album->artist->get($release->album->album_artist_id);
		$release->tracks = get_release_tracks($release_id, array( 'order_by' => 'track_disc_num, track_track_num' ));
		return $release;
	}
}

if (!function_exists('get_release_from_amazon')) {
	function get_release_from_amazon($asin, $country_name = 'United States') {
		$model = new Musicwhore_Release();
		$release = $model->get_release_from_amazon($asin, $country_name);
		return $release;
	}
}

if (!function_exists('get_release_tracks')) {
	function get_release_tracks($release_id) {
		$model = new Musicwhore_Track();
		$tracks = $model->get_release_tracks($release_id);
		return $tracks;
	}
}

if (!function_exists('get_release_tracks_from_amazon')) {
	function get_release_tracks_from_amazon($asin, $country_name = 'United States') {
		$model = new Musicwhore_Track();
		$tracks = $model->get_amazon_tracks($asin, $country_name);
		return $tracks;
	}
}

if (!function_exists('get_track')) {
	function get_track($track_id) {
		$model = new Musicwhore_Track();
		$track = $model->get_track($track_id);
		$track->release = $model->release->get_release($track->track_release_id);
		$track->release->album = $model->release->album->get_album($track->release->release_album_id);
		$track->release->album->artist = $model->release->album->artist->get_artist($track->release->album->album_artist_id);
		return $track;
	}
}
