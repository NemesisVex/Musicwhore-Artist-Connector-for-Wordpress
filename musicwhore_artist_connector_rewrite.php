<?php

/**
 * Musicwhore_Artist_Connector_Rewrite
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Connector_Rewrite')) {
	class Musicwhore_Artist_Connector_Rewrite {
		
		public function __construct() {
			add_action('init', array(&$this, 'init_rewrite_rules'));
			add_filter('query_vars', array(&$this, 'init_query_vars'));
		}

		public function init_rewrite_rules() {
			
			do_action('musicwhore_artist_connector_register_rewrite_rule');
			
			add_rewrite_rule('artist/browse/([^/]*)', 'index.php?pagename=artist&module=artist&browse=$matches[1]', 'top');
			add_rewrite_rule('artist/browse', 'index.php?pagename=artist&module=artist&browse=all', 'top');
			add_rewrite_rule('artist/albums/([^/]*)', 'index.php?pagename=artist&module=artist&section=albums&filter=$matches[1]', 'top');
			add_rewrite_rule('artist/bio/([^/]*)', 'index.php?pagename=artist&module=artist&section=bio&filter=$matches[1]', 'top');
			add_rewrite_rule('artist/posts/([^/]*)', 'index.php?pagename=artist&module=artist&section=posts&filter=$matches[1]', 'top');
			add_rewrite_rule('artist/([^/]*)', 'index.php?pagename=artist&module=artist&filter=$matches[1]', 'top');
			add_rewrite_rule('album/([^/]*)', 'index.php?pagename=artist&module=album&filter=$matches[1]', 'top');
			add_rewrite_rule('release/([^/]*)', 'index.php?pagename=artist&module=release&filter=$matches[1]', 'top');
			add_rewrite_rule('track/([^/]*)', 'index.php?pagename=artist&module=track&filter=$matches[1]', 'top');
		}

		public function init_query_vars($vars) {
			$vars[] = 'module';
			$vars[] = 'filter';
			$vars[] = 'browse';
			$vars[] = 'section';
			return $vars;
		}
	}
}

