<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 2:51 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector\Amazon;


class WebService extends WebServiceBase {

	protected $_access_key;
	protected $_secret_key;
	protected $_affiliate_id;
	protected $_enable_transient;

	public static $_locale_urls = array(
		'us' => 'ecs.amazonaws.com',
		'uk' => 'ecs.amazonaws.co.uk',
		'jp' => 'ecs.amazonaws.jp',
	);
	public static $_locale_labels = array(
		'us' => 'United States',
		'uk' => 'United Kingdom',
		'jp' => 'Japan',
	);

	public function __construct( $options = null ) {
		parent::__construct();

		if ( defined( 'MUSICWHORE_AWS_SECRET_KEY' ) === false ) {
			return false;
		}

		$this->_access_key = get_option( 'aws_access_key' );
		$this->_secret_key = MUSICWHORE_AWS_SECRET_KEY;

		if ( empty( $options['locale'] ) ) {
			$options['locale'] = 'us';
		}

		$this->setAffiliateIdByLocale( $options['locale'] );
		$this->setAwsDomainByLocale( $options['locale'] );
		$this->setAwsUrlBase();
		$this->_enable_transient = isset( $options['enable_transient'] ) ? $options['enable_transient'] : true;
	}

	public function get( $asin, $parameters = null ) {
		$cache_key = md5( $asin . MUSICWHORE_AWS_SECRET_KEY );
		$results = null;

		if ( $this->_enable_transient === true ) {
			$results = unserialize( get_transient( $cache_key ) );
		} else {
			delete_transient( $cache_key );
		}

		if ( empty( $results ) || ( $results['response']['code'] == 403 ) ) {
			if ( empty( $parameters['ResponseGroup'] ) ) {
				$parameters['ResponseGroup'] = 'ItemAttributes';
			}
			$results = parent::get( $asin, $parameters );

			if ( $this->_enable_transient === true ) {
				set_transient( $cache_key, serialize( $results ), 2 * WEEK_IN_SECONDS );
			}
		}

		return $results;
	}

	public function search( $keywords, $index, $parameters ) {
		$cache_key = md5( $keywords . $index . MUSICWHORE_AWS_SECRET_KEY );
		$results = null;

		if ( $this->_enable_transient === true ) {
			$results = unserialize( get_transient( $cache_key ) );
		} else {
			delete_transient( $cache_key );
		}

		if ( empty( $results ) ) {
			if ( empty( $parameters['ResponseGroup'] ) ) {
				$parameters['ResponseGroup'] = 'ItemAttributes';
			}
			$results = parent::search( $keywords, $index, $parameters );

			if ( $this->_enable_transient === true ) {
				set_transient( $cache_key, serialize( $results ), 2 * WEEK_IN_SECONDS );
			}
		}

		return $results;
	}


	public function setAffiliateIdByLocale( $locale = 'us' ) {
		$option_name = 'aws_affiliate_id_' . $locale;
		$this->_affiliate_id = get_option( $option_name );
	}

	public function setAwsDomainByLocale( $locale = 'us' ) {
		$this->_aws_domain = WebService::$_locale_urls[$locale];
	}

	public function enableTransient( $flag = true ) {
		$this->_enable_transient = $flag;
	}

}