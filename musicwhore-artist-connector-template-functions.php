<?php

/**
 * Musicwhore_Artist_Connector_Template_Functions
 *
 * These globally-scoped functions are available for use in themes.
 *
 * @author Greg Bueno
 */

namespace {
	use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Album;
	use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Artist;
	use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Release;
	use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models\Track;

	if ( !function_exists( 'get_all_artists' ) ) {
		function get_all_artists( $filter = null ) {
			$model = new Artist();
			$artists = $model->getArtists( $filter );
			return $artists;
		}
	}

	if ( !function_exists( 'get_artist' ) ) {
		function get_artist( $artist_id ) {
			$model = new Artist();
			$artist = $model->get( $artist_id );
			$artist->albums = get_artist_albums( $artist_id );
			if ( $artist->settings->is_classical_artist == true ) {
				$artist->classical = get_classical_albums( $artist_id );
			}
			return $artist;
		}
	}

	if ( !function_exists( 'get_artists_nav' ) ) {
		function get_artists_nav() {
			$model = new Artist();
			return $model->getArtistsNav();
		}
	}

	if ( !function_exists( 'get_artist_albums' ) ) {
		function get_artist_albums( $artist_id, $args = null ) {
			$model = new Album();
			$albums = $model->getArtistAlbums( $artist_id, $args );
			return $albums;
		}
	}

	if ( !function_exists( 'get_classical_albums' ) ) {
		function get_classical_albums( $artist_id, $args = null ) {
			$model = new Album();
			$albums = $model->getClassicalAlbums( $artist_id, $args );
			return $albums;
		}
	}

	if ( !function_exists( 'get_album' ) ) {
		function get_album( $album_id ) {
			$model = new Album();
			$album = $model->get( $album_id );
			$album->artist = $model->artist->get( $album->album_artist_id );
			$album->releases = get_album_releases( $album_id );
			return $album;
		}
	}

	if ( !function_exists( 'get_album_releases' ) ) {
		function get_album_releases( $album_id, $args = null ) {
			$release = new Release();
			return $release->getAlbumReleases( $album_id, $args );
		}
	}

	if ( !function_exists( 'get_release' ) ) {
		function get_release( $release_id ) {
			$model = new Release();
			$release = $model->get( $release_id );
			$release->album = $model->album->get( $release->release_album_id );
			$release->album->artist = $model->album->artist->get( $release->album->album_artist_id );
			$release->tracks = get_release_tracks($release_id, array( 'order_by' => 'track_disc_num, track_track_num' ));
			return $release;
		}
	}

	if ( !function_exists( 'get_release_from_amazon' ) ) {
		function get_release_from_amazon( $asin, $country_name = 'United States' ) {
			$model = new Release();
			$release = $model->getReleaseFromAmazon( $asin, $country_name );
			return $release;
		}
	}

	if ( !function_exists( 'get_release_from_musicbrainz' ) ) {
		function get_release_from_musicbrainz( $mbid, $options = array() ) {
			$model = new Release();
			$release = $model->getReleaseFromMusicbrainz( $mbid, $options );
			return $release;
		}
	}

	if ( !function_exists( 'get_release_tracks' ) ) {
		function get_release_tracks( $release_id ) {
			$model = new Track();
			$tracks = $model->getReleaseTracks( $release_id );
			return $tracks;
		}
	}

	if (!function_exists('get_release_tracks_from_amazon')) {
		function get_release_tracks_from_amazon( $asin, $country_name = 'United States' ) {
			$model = new Track();
			$tracks = $model->getAmazonTracks( $asin, $country_name );
			return $tracks;
		}
	}

	if ( !function_exists( 'get_track' ) ) {
		function get_track( $track_id ) {
			$model = new Track();
			$track = $model->get( $track_id );
			$track->release = $model->release->get( $track->track_release_id );
			$track->release->album = $model->release->album->get( $track->release->release_album_id );
			$track->release->album->artist = $model->release->album->artist->get( $track->release->album->album_artist_id );
			return $track;
		}
	}
}


