<?php

/**
 * Musicwhore_Artist_Connector_Settings
 *
 * Musicwhore_Artist_Connector_Settings contains hooks to render
 * the settings for the Musicwhore Artist Connector.
 * 
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Connector_Settings')) {
	class Musicwhore_Artist_Connector_Settings {
		
		public function __construct() {
			// Setup settings
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
		}
		
		public function admin_init() {
			register_setting('musicwhore_artist_connector-group', 'musicwhore_db_host');
			register_setting('musicwhore_artist_connector-group', 'musicwhore_db_name');
			register_setting('musicwhore_artist_connector-group', 'musicwhore_db_user');
			register_setting('musicwhore_artist_connector-group', 'aws_access_key');
			register_setting('musicwhore_artist_connector-group', 'aws_secret_key');
			register_setting('musicwhore_artist_connector-group', 'aws_affiliate_id_us');
			register_setting('musicwhore_artist_connector-group', 'aws_affiliate_id_uk');
			register_setting('musicwhore_artist_connector-group', 'aws_affiliate_id_jp');

			add_settings_section('musicwhore_artist_connector-db', 'Artist database connection', array(&$this, 'render_settings_db_description'), 'musicwhore_artist_connector');

			add_settings_field('musicwhore_artist_connector-db_host', 'Database host', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-db', array('field' => 'musicwhore_db_host'));
			add_settings_field('musicwhore_artist_connector-db_name', 'Database name', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-db', array('field' => 'musicwhore_db_name'));
			add_settings_field('musicwhore_artist_connector-db_user', 'Database user', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-db', array('field' => 'musicwhore_db_user'));
			add_settings_field('musicwhore_artist_connector-db_password', 'Database password', array(&$this, 'render_settings_input_password_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-db', array('field' => 'musicwhore_db_password'));
			
			add_settings_section('musicwhore_artist_connector-amazon', 'Amazon ecommerce API settings', array(&$this, 'render_settings_amazon_description'), 'musicwhore_artist_connector');

			add_settings_field('musicwhore_artist_connector-aws_access_key', 'Access key', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-amazon', array('field' => 'aws_access_key'));
			add_settings_field('musicwhore_artist_connector-aws_secret_key', 'Secret key', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-amazon', array('field' => 'aws_secret_key'));
			add_settings_field('musicwhore_artist_connector-aws_affiliate_id_us', 'Affiliate ID (US)', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-amazon', array('field' => 'aws_affiliate_id_us'));
			add_settings_field('musicwhore_artist_connector-aws_affiliate_id_uk', 'Affiliate ID (UK)', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-amazon', array('field' => 'aws_affiliate_id_uk'));
			add_settings_field('musicwhore_artist_connector-aws_affiliate_id_jp', 'Affiliate ID (Japan)', array(&$this, 'render_settings_input_text_field'), 'musicwhore_artist_connector', 'musicwhore_artist_connector-amazon', array('field' => 'aws_affiliate_id_jp'));
		}

		public function admin_menu() {
			add_options_page('Musicwhore Artist Connector Settings', 'Musicwhore Artist Connector', 'manage_options', 'musicwhore_artist_connector', array(&$this, 'render_connector_settings_page'));
		}

		public function render_settings_db_description() {
			echo "Connection settings for the Musicwhore.org artist database";
		}

		public function render_settings_amazon_description() {
			echo "Connection settings for Amazon ecommerce web services";
		}

		public function render_settings_input_text_field($args) {
			$field = $args['field'];
			$value = get_option($field);
			echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
		}

		public function render_settings_input_password_field($args) {
			$field = $args['field'];
			$value = get_option($field);
			echo sprintf('<input type="password" name="%s" id="%s" value="%s" />', $field, $field, $value);
		}

		public function render_connector_settings_page() {
			if (!current_user_can('manage_options')) {
				wp_die('You do not have sufficient permissions to access this page.');
			}

			include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
		}
	}
}

