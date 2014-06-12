<?php

/**
 * Musicwhore_Artist_Personnel
 *
 * @author Greg Bueno
 */

if (!class_exists('Musicwhore_Artist_Personnel')) {
	require_once(plugin_dir_path(__FILE__) . 'musicwhore_model.php');

	class Musicwhore_Artist_Personnel extends Musicwhore_Model {

		public $_table = 'mw_artists_personnel';
		public $_primary_key = 'member_id';

		public function __construct() {
			parent::__construct();
		}

		public function get($id, $args = null) {
			$member = parent::get($id, $args);
			if (!empty($member)) {
				$member->member_name = $this->format_artist_name($member);
			}
			return $member;
		}

		public function get_artist_members($id, $args = null) {
			$members = $this->get_many_by('member_parent_id', $id, $args);

			$_this = $this;
			array_walk($members, function ($member) use ($_this) {
				$member->member_name = $_this->format_member_name($member);
			});

			return $members;
		}

		public function format_member_name($member) {
			$member_display_name = null;
			if (empty($member->member_display_name)) {
				if (empty($member->artist_first_name)) {
					$member_display_name = $member->artist_last_name;
				} else {
					$member_display_name = $member->artist_first_name . ' ' . $member->artist_last_name;
				}
			} else {
				$member_display_name = $member->member_display_name;
			}

			return $member_display_name;
		}

	}
	
}

