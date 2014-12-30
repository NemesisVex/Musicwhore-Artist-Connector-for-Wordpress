<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 12/26/14
 * Time: 11:42 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class Artist extends Base {

	public $_table = 'mw_artists';
	public $_primary_key = 'artist_id';

	public function __construct() {
		parent::__construct();
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\ArtistPersonnel', 'alias' => 'members' ) );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\ArtistMeta', 'alias' => 'meta' ) );
	}

	public function get( $id, $args = null ) {
		$artist = parent::get( $id, $args );
		if ( !empty( $artist ) ) {
			$this->meta->load( $id );

			$artist->settings = $this->meta->getSettings();
			$artist->artist_name = $this->formatArtistName($artist);
			$artist->artist_members = $this->members->getArtistMembers( $id, array(
				'order_by' => 'member_order',
			) );
		}
		return $artist;
	}

	public function getArtists($filter = null) {
		if ( !empty( $filter ) ) {
			$artists = $this->getManyLike('artist_last_name', $filter, 'after', array( 'order_by' => 'artist_last_name' ));
		} else {
			$artists = $this->getAll( array( 'order_by' => 'artist_last_name' ) );
		}

		$_this = $this;
		array_walk( $artists, function ( $artist ) use ( $_this ) {
			$artist->artist_name = $_this->formatArtistName($artist);
		});

		return $artists;
	}

	public function getArtistsNav() {
		$nav = $this->mw_db->get_results( 'select upper( substring( artist_last_name from 1 for 1 ) ) as nav from mw_artists group by nav order By nav' );
		return $nav;
	}

	public function formatArtistName($artist) {
		$artist_display_name = null;
		if (empty( $artist->artist_first_name ) ) {
			$artist_display_name = $artist->artist_last_name;
		} else {
			if ( empty( $artist->settings->is_asian_name ) ) {
				$this->meta->load( $artist->artist_id );
				$artist->settings = $this->meta->getSettings();
			}
			$artist_display_name = ( $artist->settings->is_asian_name == true ) ? $artist->artist_last_name . ' '  .$artist->artist_first_name : $artist->artist_first_name . ' ' . $artist->artist_last_name;
		}

		return $artist_display_name;
	}

}