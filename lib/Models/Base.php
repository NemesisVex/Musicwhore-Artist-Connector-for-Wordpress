<?php
/**
 * Created by PhpStorm.
 * User: gregbueno
 * Date: 12/26/14
 * Time: 11:40 AM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Models;


class Base {
	private $is_driver_ready;

	protected $mw_db;

	public $_table;
	public $_primary_key;

	public function __construct() {
		$driver = new Driver();
		$this->mw_db = $driver->getDriver();
		$this->is_driver_ready = $driver->isReady();
	}

	public function get($id, $args = null) {
		$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

		$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $this->_primary_key = %d", $id);

		$result = $this->mw_db->get_row($prepared_query);

		return $result;
	}

	public function getAll($args = null) {
		$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

		$order_by = $args['order_by'];
		$order = is_array($order_by) ? implode(", ", $order_by) : $order_by;

		if (!empty($order)) {
			$prepared_query = "select $fields from $this->_table order by $order";
		} else {
			$prepared_query = "select $fields from $this->_table";
		}

		$result = $this->mw_db->get_results($prepared_query);

		return $result;
	}

	public function getBy($field, $value, $args = null) {
		$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

		$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $field = %s", $value);

		$result = $this->mw_db->get_row($prepared_query);

		return $result;
	}

	public function getManyBy($field, $value, $args = null) {
		$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

		$order_by = $args['order_by'];
		$order = is_array($order_by) ? implode(", ", $order_by) : $order_by;

		if (!empty($order)) {
			$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $field = %s order by $order", $value);
		} else {
			$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $field = %s", $value);
		}

		$result = $this->mw_db->get_results($prepared_query);

		return $result;
	}

	public function getManyLike($field, $value, $pos = 'all', $args = null) {
		$fields = !empty($args['fields']) ? implode(", ", $args['fields']) : '*';

		$order_by = $args['order_by'];
		$order = is_array($order_by) ? implode(", ", $order_by) : $order_by;

		switch ($pos) {
			case 'before':
				$like_value = '%' . like_escape($value);
				break;
			case 'after':
				$like_value = like_escape($value) . '%';
				break;
			case 'all':
			default:
				$like_value = '%' . like_escape($value) . '%';
		}

		if (!empty($order)) {
			$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $field like %s order by $order", $like_value);
		} else {
			$prepared_query = $this->mw_db->prepare( "select $fields from $this->_table where $field like %s", $like_value);
		}

		$result = $this->mw_db->get_results($prepared_query);

		return $result;
	}

	public function loadRelationship($args) {

		$model = null;
		if (is_array($args)) {
			$model = $args['model'];
			$alias = $args['alias'];
		} else {
			$model = $alias = $args;
		}

		$this->{$alias} = new $model();
	}

	public function getDriverStatus() {
		return $this->is_driver_ready;
	}

}