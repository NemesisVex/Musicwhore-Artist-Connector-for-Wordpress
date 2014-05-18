<?php

/**
 * Musicwhore_Artist_Connector_Aws
 *
 * @author gregbueno
 */

require_once(plugin_dir_path(__FILE__) . 'musicwhore_artist_connector_aws_base.php');

if (!class_exists('Musicwhore_Artist_Connector_Aws')) {
	class Musicwhore_Artist_Connector_Aws extends Musicwhore_Artist_Connector_Aws_Base {
		
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
		
		public function __construct($options = null) {
			parent::__construct();
			
			$this->_access_key = get_option('aws_access_key');
			$this->_secret_key = get_option('aws_secret_key');
			
			if (empty($options['locale'])) {
				$options['locale'] = 'us';
			}
			
			$this->set_affiliate_id_by_locale($options['locale']);
			$this->set_aws_domain_by_locale($options['locale']);
			$this->set_aws_url_base();
			$this->_enable_transient = isset($options['enable_transient']) ? $options['enable_transient'] : true;
		}
		
		public function get($asin, $parameters = null) {
			$cache_key = md5($asin);
			$results = null;
			
			if ($this->_enable_transient === true) {
				$results = unserialize(get_transient($cache_key));
			} else {
				echo intval($this->_enable_transient);
				delete_transient($cache_key);
			}
			
			if (empty($results)) {
				if (empty($parameters['ResponseGroup'])) {
					$parameters['ResponseGroup'] = 'ItemAttributes';
				}
				$results = parent::get($asin, $parameters);
				
				if ($this->_enable_transient === true) {
					set_transient($cache_key, serialize($results), 2 * WEEK_IN_SECONDS);
				}
			}
			
			return $results;
		}
		
		public function search($keywords, $index, $parameters) {
			$cache_key = md5($keywords . $index);
			$results = null;
			
			if ($this->_enable_transient === true) {
				$results = unserialize(get_transient($cache_key));
			} else {
				delete_transient($cache_key);
			}
			
			if (empty($results)) {
				if (empty($parameters['ResponseGroup'])) {
					$parameters['ResponseGroup'] = 'ItemAttributes';
				}
				$results = parent::search($keywords, $index, $parameters);
				
				if ($this->_enable_transient === true) {
					set_transient($cache_key, serialize($results), 2 * WEEK_IN_SECONDS);
				}
			}
			
			return $results;
		}


		public function set_affiliate_id_by_locale($locale = 'us') {
			$option_name = 'aws_affiliate_id_' . $locale;
			$this->_affiliate_id = get_option($option_name);
		}
		
		public function set_aws_domain_by_locale($locale = 'us') {
			$this->_aws_domain = Musicwhore_Artist_Connector_Aws::$_locale_urls[$locale];
		}
		
		public function enable_transient($flag = true) {
			$this->_enable_transient = $flag;
		}
	}
}

