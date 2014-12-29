<?php
/**
 * Created by PhpStorm.
 * User: gbueno
 * Date: 12/29/2014
 * Time: 2:34 PM
 */

namespace VigilantMedia\WordPress\Plugins\MusicwhoreOrg\ArtistConnector;


class Rewrite {

	public function __construct() {

	}

	public static function init() {
		add_action( 'init', array( __CLASS__, 'initRewriteRules' ) );
		add_filter( 'query_vars', array( __CLASS__, 'initQueryVars' ) );
	}

	public function initRewriteRules() {

		do_action('musicwhore_artist_connector_register_rewrite_rule');

		add_rewrite_rule( 'artist/browse/([^/]*)', 'index.php?pagename=artist&module=artist&browse=$matches[1]', 'top' );
		add_rewrite_rule( 'artist/browse', 'index.php?pagename=artist&module=artist&browse=all', 'top' );
		add_rewrite_rule( 'artist/albums/([^/]*)', 'index.php?pagename=artist&module=artist&section=albums&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'artist/bio/([^/]*)', 'index.php?pagename=artist&module=artist&section=bio&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'artist/posts/([^/]*)', 'index.php?pagename=artist&module=artist&section=posts&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'artist/([^/]*)', 'index.php?pagename=artist&module=artist&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'album/([^/]*)', 'index.php?pagename=artist&module=album&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'release/([^/]*)', 'index.php?pagename=artist&module=release&filter=$matches[1]', 'top' );
		add_rewrite_rule( 'track/([^/]*)', 'index.php?pagename=artist&module=track&filter=$matches[1]', 'top' );
	}

	public function initQueryVars( $vars ) {
		$vars[] = 'module';
		$vars[] = 'filter';
		$vars[] = 'browse';
		$vars[] = 'section';
		return $vars;
	}

}