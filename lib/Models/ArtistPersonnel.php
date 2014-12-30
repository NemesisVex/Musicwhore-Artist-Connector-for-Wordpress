<?php

/**
 * ArtistPersonnel
 *
 * @author Greg Bueno
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;

class ArtistPersonnel extends Base {

	public $_table = 'mw_artists_personnel';
	public $_primary_key = 'member_id';

	public function __construct() {
		parent::__construct();
	}

	public function get( $id, $args = null ) {
		$member = parent::get( $id, $args );
		if ( !empty( $member ) ) {
			$member->member_name = $this->formatMemberName( $member );
		}
		return $member;
	}

	public function getArtistMembers( $id, $args = null ) {
		$members = $this->getManyBy( 'member_parent_id', $id, $args );

		$_this = $this;
		array_walk($members, function ( $member ) use ( $_this ) {
			$member->member_name = $_this->formatMemberName( $member );
		});

		return $members;
	}

	public function formatMemberName( $member ) {
		$member_display_name = null;
		if ( empty( $member->member_display_name ) ) {
			if ( empty( $member->artist_first_name ) ) {
				$member_display_name = $member->artist_last_name;
			} else {
				$member_display_name = $member->artist_first_name . ' ' . $member->artist_last_name;
			}
		} else {
			$member_display_name = $member->member_display_name;
		}

		return $member_display_name;
	}

}
