<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 6/11/14
 * Time: 11:13 AM
 */

if (!class_exists('Musicwhore_Base_Meta')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');

	class Musicwhore_Base_Meta extends Musicwhore_Model {

		public $_primary_key = 'meta_id';
		protected $items;

		public function __construct() {
			parent::__construct();
		}

		public function load($key, $id) {
			$this->items = $this->get_by($key, $id);
		}

		public function get_meta($name) {
			return $this->get_by('meta_field_name', $name);
		}

	}
}

