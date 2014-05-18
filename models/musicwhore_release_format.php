<?php

/**
 * Musicwhore_Release_Format
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Release_Format')) {
	require_once(plugin_dir_path(__FILE__). 'musicwhore_model.php');

	class Musicwhore_Release_Format extends Musicwhore_Model {

		public $_table = 'mw_albums_formats';
		public $_primary_key = 'format_id';

		public function __construct() {
			parent::__construct();
		}

	}
}

