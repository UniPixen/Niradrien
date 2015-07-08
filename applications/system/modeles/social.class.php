<?php 
	class social extends base {
		function __construct() {
			$this->tableName = 'social_networks';
			$this->idColumn = 'id';
		}

		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql, $language;
			
			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}
			
			if ($where != '') {
				$where = " WHERE " . $where;
			}
			
			$mysql->query("
				SELECT *
				FROM social_networks
				$where
				ORDER BY id desc
				$limitQuery
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			while ($d = $mysql->fetch_array()) {
				$return[strtolower($d['name'])] = $d;
			}
			
			$this->foundRows = $mysql->getFoundRows();
			
			return $return;
		}
		
		public function get($id) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM social_networks
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			return $mysql->fetch_array();
		}

		public function add() {
			global $mysql, $langArray, $config;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['icon']) || trim($_POST['icon']) == '') {
				$error['icon'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['color']) || trim($_POST['color']) == '') {
				$error['color'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['site_username']) || trim($_POST['site_username']) == '') {
				$error['site_username'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['url']) || trim($_POST['url']) == '') {
				$error['url'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$mysql->query("
				INSERT INTO social_networks (
					name,
					icon,
					color,
					site_username,
					url,
					visible
				)
				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['icon']) . "',
					'" . sql_quote($_POST['color']) . "',
					'" . sql_quote($_POST['site_username']) . "',
					'" . sql_quote($_POST['url']) . "',
					'" . sql_quote($_POST['visible']) . "'
				)
			", __FUNCTION__ );
			
			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['icon']) || trim($_POST['icon']) == '') {
				$error['icon'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['color']) || trim($_POST['color']) == '') {
				$error['color'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['site_username']) || trim($_POST['site_username']) == '') {
				$error['site_username'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['url']) || trim($_POST['url']) == '') {
				$error['url'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$mysql->query("
				UPDATE social_networks
				SET name = '" . sql_quote($_POST['name']) . "',
					icon = '" . sql_quote($_POST['icon']) . "',
					color = '" . sql_quote($_POST['color']) . "',
					site_username = '" . sql_quote($_POST['site_username']) . "',
					url = '" . sql_quote($_POST['url']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}

		public function delete($id) {
			global $mysql;
			
			$mysql->query("
				DELETE FROM social_networks
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}
	}
?>