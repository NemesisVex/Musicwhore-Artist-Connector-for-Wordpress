<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 12/26/14
 * Time: 11:43 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class Meta extends Base {

	public $_primary_key = 'meta_id';
	protected $items;
	protected $settings;

	public function __construct() {
		parent::__construct();
	}

	public function load( $key, $id ) {
		$this->items = $this->get_many_by( $key, $id );

		$settings = array();
		foreach ( $this->items as $item ) {
			$settings[$item->meta_field_name] = $item->meta_field_value;
		}
		$this->settings = (object) $settings;
	}

	public function getSettings() {
		return $this->settings;
	}

	public function getSetting( $name ) {
		return $this->settings[$name];
	}

	public function getMeta( $name ) {
		return $this->get_by( 'meta_field_name', $name );
	}

}