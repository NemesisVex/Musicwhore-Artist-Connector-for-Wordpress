<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/23/2014
 * Time: 3:00 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;


class Settings {

	public function __construct() {

	}

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'wpEnqueueScripts' ) );
		add_action( 'admin_init', array( __CLASS__, 'wpEnqueueStyles' ) );

		add_action( 'admin_init', array( __CLASS__, 'adminInit'));
		add_action( 'admin_menu', array( __CLASS__, 'adminMenu'));
	}

	public static function adminInit() {
		$domain_group = WP_PLUGIN_DOMAIN . '-group';
		register_setting( $domain_group, 'musicwhore_db_host' );
		register_setting( $domain_group, 'musicwhore_db_name' );
		register_setting( $domain_group, 'musicwhore_db_user' );
		register_setting( $domain_group, 'musicwhore_db_password' );
		register_setting( $domain_group, 'aws_access_key' );
		register_setting( $domain_group, 'aws_affiliate_id_us' );
		register_setting( $domain_group, 'aws_affiliate_id_uk' );
		register_setting( $domain_group, 'aws_affiliate_id_jp' );

		$domain_db = WP_PLUGIN_DOMAIN . '-db';
		add_settings_section( $domain_db, 'Artist database connection', array( __CLASS__, 'renderDbDescription'), WP_PLUGIN_DOMAIN);

		add_settings_field( WP_PLUGIN_DOMAIN . '-db_host', 'Database host', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_db, array('field' => 'musicwhore_db_host'));
		add_settings_field( WP_PLUGIN_DOMAIN . '-db_name', 'Database name', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_db, array('field' => 'musicwhore_db_name'));
		add_settings_field( WP_PLUGIN_DOMAIN . '-db_user', 'Database user', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_db, array('field' => 'musicwhore_db_user'));
		add_settings_field( WP_PLUGIN_DOMAIN . '-db_password', 'Database password', array( __CLASS__, 'renderInputPasswordField'), WP_PLUGIN_DOMAIN, $domain_db, array('field' => 'musicwhore_db_password'));

		add_settings_section(  WP_PLUGIN_DOMAIN . '-amazon', 'Amazon ecommerce API settings', array( __CLASS__, 'renderAmazonDescription'), WP_PLUGIN_DOMAIN);

		$domain_amazon = WP_PLUGIN_DOMAIN . '-amazon';
		add_settings_field(  WP_PLUGIN_DOMAIN . '-aws_access_key', 'Access key', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_amazon, array('field' => 'aws_access_key'));
		add_settings_field(  WP_PLUGIN_DOMAIN . '-aws_affiliate_id_us', 'Affiliate ID (US)', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_amazon, array('field' => 'aws_affiliate_id_us'));
		add_settings_field(  WP_PLUGIN_DOMAIN . '-aws_affiliate_id_uk', 'Affiliate ID (UK)', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_amazon, array('field' => 'aws_affiliate_id_uk'));
		add_settings_field(  WP_PLUGIN_DOMAIN . '-aws_affiliate_id_jp', 'Affiliate ID (Japan)', array( __CLASS__, 'renderInputTextField'), WP_PLUGIN_DOMAIN, $domain_amazon, array('field' => 'aws_affiliate_id_jp'));
	}

	public static function adminMenu() {
		add_options_page('Musicwhore Artist Connector Settings', 'Musicwhore Artist Connector', 'manage_options', WP_PLUGIN_DOMAIN, array( __CLASS__, 'renderConnectorSettingsPage'));
	}

	public static function wpEnqueueScripts() {
		wp_enqueue_script( 'chosen-js', plugin_dir_url(__FILE__) . 'js/chosen/chosen.jquery.min.js');
	}

	public static function wpEnqueueStyles() {
		wp_enqueue_style( 'chosen-css', plugin_dir_url(__FILE__) . 'js/chosen/chosen.min.css' );
		wp_enqueue_style( 'mw-meta-css', plugin_dir_url(__FILE__) . 'css/layout.css' );
	}

	public function renderDbDescription() {
		echo "Connection settings for the Musicwhore.org artist database.";
	}

	public function renderAmazonDescription() {
		$secret_status = defined('MUSICWHORE_AWS_SECRET_KEY') === true ? 'set' : 'unset';
		$description = 'Connection settings for Amazon ecommerce web services. The secret key is currently ' . $secret_status . '.';
		echo $description;
	}

	public function renderInputTextField($args) {
		$field = $args['field'];
		$value = get_option($field);
		echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
	}

	public function renderInputPasswordField($args) {
		$field = $args['field'];
		$value = get_option($field);
		echo sprintf('<input type="password" name="%s" id="%s" value="%s" />', $field, $field, $value);
	}

	public function renderConnectorSettingsPage() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		include( sprintf( "%s/Views/settings.php", plugin_dir_path( __FILE__ ) ) );
	}

}