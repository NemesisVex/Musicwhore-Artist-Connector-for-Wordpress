<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/30/2014
 * Time: 8:25 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


use VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Amazon\WebService;

class Track extends Base {

	public $_table = 'mw_albums_tracks';
	public $_primary_key = 'track_id';

	public function __construct() {
		parent::__construct();
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\Release', 'alias' => 'release' ) );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\TrackMeta', 'alias' => 'meta' ) );
	}

	public function getReleaseTracks( $release_id, $args = null ) {
		if  (empty( $args['order_by'] ) ) {
			$args['order_by'] = 'track_disc_num, track_track_num';
		}
		$tracks = $this->getManyBy( 'track_release_id', $release_id, $args );

		$_this = $this;
		array_walk( $tracks, function ( $track ) use ( $_this ) {
			$this->meta->load( $track->track_id );
			$track->settings = $this->meta->getSettings();
		});

		return $tracks;
	}

	public function getAmazonTracks( $asin, $country_name = 'United States' ) {

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

		$aws_tracks = $aws_results->Items->Item->Tracks;
		$tracks = Track::parseAwsTracks( $aws_tracks );

		return $tracks;
	}

	public static function parseAwsTracks( $aws_tracks ) {
		$tracks = array();

		if ( !empty( $aws_tracks->Disc ) ) {
			foreach ( $aws_tracks->Disc as $aws_disc ) {
				$disc_num = (int) $aws_disc->attributes()->Number;
				$track = array();
				$t = 1;
				foreach ( $aws_disc->Track as $aws_track ) {
					$track['track_disc_num'] = $disc_num;
					$track['track_track_num'] = $t;
					$track['track_song_title'] = (string) $aws_track;
					$t++;

					$tracks[] = (object) $track;
				}
			}
		}

		return $tracks;
	}

}