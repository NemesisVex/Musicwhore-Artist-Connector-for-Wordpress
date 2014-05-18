<?php

/**
 * Musicwhore_Artist_Connector_Aws_Base
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Connector_Aws_Base')) {
	class Musicwhore_Artist_Connector_Aws_Base {

		protected $_access_key;
		protected $_secret_key;
		protected $_affiliate_id;
		protected $_aws_domain;
		protected $_aws_path;
		protected $_aws_url_base;

		public function __construct() {
			$this->_affiliate_id = 'webservices-20';
			$this->_aws_domain = 'ecs.amazonaws.com';
			$this->_aws_path = '/onca/xml';
			$this->_aws_url_base = $this->set_aws_url_base();
		}
		
		public function get($asin, $more_parameters = null) {
			$parameters['ItemId'] = $asin;
			$parameters['Operation'] = 'ItemLookup';
			if (!empty($more_parameters)) {$parameters = array_merge($parameters, $more_parameters);}
			
			$url = $this->build_request_uri($parameters);
			if (!empty($url)) {
				$results = $this->send_request($url);
				return $results;
			}
		}
		
		public function search($keywords, $search_index = 'Music', $more_parameters = null) {
			$parameters['SearchIndex'] = $search_index;
			$parameters['Keywords'] = $keywords;
			$parameters['Operation'] = 'ItemSearch';
			if (!empty($more_parameters)) {$parameters = array_merge($parameters, $more_parameters);}
			
			$url = $this->build_request_uri($parameters);
			if (!empty($url)) {
				$results = $this->send_request($url);
				return $results;
			}
		}
		
		public function build_request_uri( $parameters ) {
			
			if (empty($this->_access_key)
					|| empty($this->_secret_key)
					|| empty($this->_affiliate_id)
					|| empty($parameters['Operation'])) {
				return false;
			}
			
			$base_parameters['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
			$base_parameters['Version'] = '2009-03-01';
			$base_parameters['AWSAccessKeyId'] = $this->_access_key;
			$base_parameters['Service'] = 'AWSECommerceService';
			$base_parameters['AssociateTag'] = $this->_affiliate_id;
			
			$request_parameters = array_merge($base_parameters, $parameters);
			
			// Sort paramters
			ksort($request_parameters);

			// re-build the request
			$request_array = array();
			foreach ($request_parameters as $parameter => $value)
			{
				$parameter = str_replace("%7E", "~", rawurlencode($parameter));
				$value = str_replace("%7E", "~", rawurlencode($value));
				$request_array[] = $parameter . "=" . $value;
			}
			$request = implode("&", $request_array);
			
			$signature_string = "GET" . chr(10) . $this->_aws_domain . chr(10) . $this->_aws_path . chr(10) . $request;

			$signature = urlencode(base64_encode(hash_hmac("sha256", $signature_string, $this->_secret_key, true)));

			$request = $this->_aws_url_base . '?' . $request . "&Signature=" . $signature;
			
			return $request;
		}
		
		public function send_request($url) {
			return wp_remote_get($url);
		}
		
		protected function set_aws_url_base($domain = null, $path = null) {
			if (empty($domain)) {
				$domain = $this->_aws_domain;
			}
			
			if (empty($path)) {
				$path = $this->_aws_path;
			}
			
			$this->_aws_url_base = 'http://' . $domain . $path;
		}
	}
}

