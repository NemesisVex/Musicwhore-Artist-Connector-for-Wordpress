<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 11:22 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class Album extends Base {

	public $_table = 'mw_albums';
	public $_primary_key = 'album_id';

	public function __construct() {
		parent::__construct();
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\Artist', 'alias' => 'artist') );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\AlbumFormat', 'alias' => 'format') );
		$this->loadRelationship( array( 'model' => __NAMESPACE__ . '\AlbumMeta', 'alias' => 'meta') );
	}

	public function get( $id, $args = null ) {
		$album = parent::get( $id, $args );
		if ( !empty( $album ) ) {
			$album->album_format = $this->format->get( $album->album_format_id );

			$this->meta->load( $id );
			$album->settings = $this->meta->getSettings();
		}
		return $album;
	}

	public function getArtistAlbums( $artist_id, $args = null ) {
		$albums = $this->getManyBy( 'album_artist_id', $artist_id, $args );
		$formats = $this->format->getAll();
		$_this = $this;
		array_walk( $albums, function ( $album ) use ( $_this, $formats ) {
			foreach ( $formats as $format ) {
				if ( $album->album_format_id == $format->format_id ) {
					$album->album_format = $format;
				}
			}
		});
		return $albums;
	}

	public function getClassicalAlbums( $artist_id, $args = null ) {
		$fields = !empty( $args['fields'] ) ? implode( ", ", $args['fields'] ) : '*';

		$results = (object)array();

		$soloist_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'soloist_id' and meta_field_value = %d", $artist_id );
		$results->soloist = $this->mw_db->get_results($soloist_query);

		$ensemble_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'ensemble_id' and meta_field_value = %d", $artist_id );
		$results->ensemble = $this->mw_db->get_results($ensemble_query);

		$conductor_query = $this->mw_db->prepare( "select $fields from " . $this->meta->_table . " as m left join $this->_table as a on m.meta_album_id = a.album_id where meta_field_name = 'conductor_id' and meta_field_value = %d", $artist_id );
		$results->conductor = $this->mw_db->get_results($conductor_query);

		$formats = $this->format->getAll();
		$_this = $this;

		foreach ( $results as $albums ) {
			array_walk( $albums, function ($album) use ( $_this, $formats ) {
				foreach ( $formats as $format ) {
					if ( $album->album_format_id == $format->format_id ) {
						$album->album_format = $format;
					}
				}
			});
		}

		return $results;
	}

}