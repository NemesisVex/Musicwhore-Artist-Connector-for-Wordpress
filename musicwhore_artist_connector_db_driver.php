<?php

/**
 * Musicwhore_Artist_Connector_Db_Driver
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Connector_Db_Driver')) {
	class Musicwhore_Artist_Connector_Db_Driver {
		
		private $mw_db;
		private $is_ready;
		private $status_message;
		
		public function __construct() {
			$this->is_ready = false;
			
			$this->init();
		}
		
		public function init() {
			global $wpdb;
			
			// Establish a connection to configured database.
			// Use the WP database if none is configured.
			$mt_db_host = get_option('musicwhore_db_host', DB_HOST);
			if (empty($mt_db_host)) {
				$mt_db_host = DB_HOST;
			}

			$mt_db_name = get_option('musicwhore_db_name', DB_NAME);
			if (empty($mt_db_name)) {
				$mt_db_name = DB_NAME;
			}

			$mt_db_user = get_option('musicwhore_db_user', DB_USER);
			if (empty($mt_db_user)) {
				$mt_db_user = DB_USER;
			}

			$mt_db_password = get_option('musicwhore_db_password', DB_PASSWORD);
			if (empty($mt_db_password)) {
				$mt_db_password = DB_PASSWORD;
			}

			if (false === $this->mw_db = new wpdb($mt_db_user, $mt_db_password, $mt_db_name, $mt_db_host)) {
				$this->mw_db = $wpdb;
			}
			
			// Exit if we don't have at least the artist table.
			$mw_artist_table = $this->mw_db->get_var("show tables like 'mw_artists';");
			if ($mw_artist_table != 'mw_artists') {
				$this->mw_db = null;
				$this->status_message = 'Musicwhore.org Artist table was not found.';
				return false;
			}

			// Everything look cool?
			$this->is_ready = true;
		}
		
		public function is_ready() {
			return $this->is_ready;
		}
		
		public function get_driver() {
			return $this->mw_db;
		}
		
		public function get_status() {
			return $this->status_message;
		}
	}
}

