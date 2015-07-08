<?php
	class percents extends base {
		function __construct() {
			$this->tableName = 'percents';
		}

		public function getAll() {
			global $mysql, $language;

			$mysql->query("
				SELECT *
				FROM percents
				ORDER BY percent ASC
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM percents
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function getPercentRow($member) {
			global $mysql, $meta;

			$mysql->query("
				SELECT commission_percent
				FROM members
				WHERE member_id = " . (int)$member['member_id'] . "
				LIMIT 1
			");

			$member_data = $mysql->fetch_array();

			if ($member_data && round($member_data['commission_percent']) > 0) {
				return array('percent' => floatval($member_data['commission_percent']), 'to' => 0);
			}

			$no_exclusive_author_percent = 60;

			if (isset($meta['no_exclusive_author_percent'])) {
				$no_exclusive_author_percent = (int)$meta['no_exclusive_author_percent'];
			}

			$exclusive_author_percent = 70;

			if (isset($meta['exclusive_author_percent'])) {
				$exclusive_author_percent = (int)$meta['exclusive_author_percent'];
			}

			if ($member['exclusive_author'] == 'false') {
				$percent = array('percent' => $no_exclusive_author_percent, 'to' => 0);
			}

			else {
				$mysql->query("
					SELECT *
					FROM percents
					WHERE from <= '" . sql_quote($member['sold']) . "' AND (to > '" . sql_quote($member['sold']) . "' OR to = '0')
				");

				if ($mysql->num_rows() == 0) {
					$percent = array('percent' => $exclusive_author_percent, 'to' => 0);
				}

				else {
					$percent = $mysql->fetch_array();
				}
			}

			return $percent;
		}

		public function add() {
			global $mysql, $langArray, $config;

			if (!isset($_POST['percent']) || trim($_POST['percent']) == '' || !is_numeric($_POST['percent'])) {
				$error['percent'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['from']) || trim($_POST['from']) == '' || !is_numeric($_POST['from'])) {
				$error['from'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['to']) || trim($_POST['to']) == '' || !is_numeric($_POST['to'])) {
				$error['to'] = $langArray['error_fill_this_field'];
			}

			if (isset($_POST['from']) && isset($_POST['to']) && $_POST['from'] != '' && $_POST['to'] != '' && $_POST['to'] != '0' && $_POST['from'] >= $_POST['to']) {
				$error['from'] = $langArray['error_from_over_to'];
				$error['to'] = $langArray['error_from_over_to'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				INSERT INTO percents (
					percent,
					from,
					to
				)

				VALUES (
					'" . sql_quote($_POST['percent']) . "',
					'" . sql_quote($_POST['from']) . "',
					'" . sql_quote($_POST['to']) . "'
				)
			", __FUNCTION__ );

			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;
			
			if (!isset($_POST['percent']) || trim($_POST['percent']) == '' || !is_numeric($_POST['percent'])) {
				$error['percent'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['from']) || trim($_POST['from']) == '' || !is_numeric($_POST['from'])) {
				$error['from'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['to']) || trim($_POST['to']) == '' || !is_numeric($_POST['to'])) {
				$error['to'] = $langArray['error_fill_this_field'];
			}

			if (isset($_POST['from']) && isset($_POST['to']) && $_POST['from'] != '' && $_POST['to'] != '' && $_POST['to'] != '0' && $_POST['from'] >= $_POST['to']) {
				$error['from'] = $langArray['error_from_over_to'];
				$error['to'] = $langArray['error_from_over_to'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE percents
				SET percent = '" . sql_quote($_POST['percent']) . "',
					from = '" . sql_quote($_POST['from']) . "',
					to = '" . sql_quote($_POST['to']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		public function delete($id) {
			global $mysql;

			$mysql->query("
				DELETE FROM percents
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__);
			return true;
		}
	}
?>