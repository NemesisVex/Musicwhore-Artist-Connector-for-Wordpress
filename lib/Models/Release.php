<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 12:04 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Amazon\WebService;

class Release extends Base {

	public $_table = 'mw_albums_releases';
	public $_primary_key = 'release_id';

	public function __construct() {
		parent::__construct();
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\Album', 'alias' => 'album') );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\ReleaseFormat', 'alias' => 'format' ) );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\ReleaseMeta', 'alias' => 'meta' ) );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\AlbumMusicbrainz', 'alias' => 'musicbrainz' ) );
	}

	public function get( $id, $args = null ) {
		$release = parent::get( $id, $args );
		if ( !empty( $release ) ) {
			$format = $this->format->get( $release->release_format_id );
			$release->release_format_name = $format->format_name;
			$release->release_format_alias = $format->format_alias;
			$release->release_musicbrainz_id = $this->musicbrainz->getManyBy( 'mb_album_id', $id );

			$this->meta->load($id);
			$release->settings = $this->meta->getSettings();
		}
		return $release;
	}

	public function getAlbumReleases( $album_id ) {
		$releases = $this->getManyBy( 'release_album_id', $album_id, $args );

		if ( !empty( $releases ) ) {
			$formats = $this->format->getAll();
			array_walk( $releases, function ( $release ) use ( $formats ) {
				foreach ( $formats as $format ) {
					if ( $format->format_id == $release->release_format_id ) {
						$release->release_format_name = $format->format_name;
						$release->release_format_alias = $format->format_alias;
					}
				}
			});
		}

		return $releases;
	}

	public function getReleaseFromAmazon( $asin, $country_name = 'United States' ) {

		$locale = array_search( $country_name, WebService::$_locale_labels );
		if (empty($locale)) {
			$locale = 'us';
		}

		$aws = new WebService( array( 'locale' => $locale ) );

		$parameters['ResponseGroup'] = 'Large';
		$wp_results = $aws->get( $asin, $parameters );
		$aws_results = simplexml_load_string( $wp_results['body'] );

		if ( !empty( $aws_results->Request->Errors ) ) {
			throw new Exception( $aws_results->Request->Errors->Error->Message );
		}

		return $aws_results->Items->Item;
	}

	public function getReleaseFromMusicbrainz( $mbid, $options = array() ) {
		if ( empty( $options ) ) {
			$options = array(
				'artists',
				'labels',
				'recordings',
			);
		}
		$mb_url = 'http://musicbrainz.org/ws/2/release/' . $mbid . '?inc=' . implode( '+', $options );
		$wp_results = wp_remote_get( $mb_url );
		$mb_results = simplexml_load_string( $wp_results['body'] );

		return $mb_results;
	}

}