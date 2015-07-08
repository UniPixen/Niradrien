<?php
	class languages extends base {
		function __construct() {
			$this->tableName = 'languages';
			$this->uploadFileDirectory = 'languages/';
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
				SELECT SQL_CALC_FOUND_ROWS *
				FROM languages
				$where
				ORDER BY order_index ASC
				$limitQuery
				", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			
			while($d = $mysql->fetch_array()) {
				$return[$d['code']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id, $field = 'id') {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM languages
				WHERE " . sql_quote($field) . " = '" . sql_quote($id) . "'
				", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			return $mysql->fetch_array();
		}

		public function getDefault() {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM languages
				WHERE order_index = 1
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

			$flag = $this->upload('flag', '', false);
			
			if (substr($flag, 0, 6) == 'error_') {
				$error['flag'] = $langArray[$flag];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}

			$orderIndex = $this->getNextOrderIndex();

			$mysql->query("
				INSERT INTO languages (
					name,
					code,
					locale,
					locale_territory,
					flag,
					visible,
					order_index
				)

				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['code']) . "',
					'" . sql_quote($_POST['locale']) . "',
					'" . sql_quote($_POST['locale_territory']) . "',
					'" . sql_quote($flag) . "',
					'" . sql_quote($_POST['visible']) . "',
					'" . intval($orderIndex) . "'
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

			$flag = $this->upload('flag', '', false);
			if (substr($flag, 0, 6) == 'error_') {
				$error['flag'] = $langArray[$flag];
			}

			if (isset($error)) {
				return $error;
			}

			$setQuery = '';
			if ($flag != '' || isset($_POST['deleteFlag'])) {
				$this->deleteFlag($id);
			}

			if ($flag != '') {
				$setQuery .= " flag = '" . sql_quote($flag) . "', ";
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = '0';
			}

			$mysql->query("
				UPDATE languages
				SET name = '" . sql_quote($_POST['name']) . "',
					$setQuery
					flag = '" . sql_quote($flag) . "',
					code = '" . sql_quote($_POST['code']) . "',
					visible = '" . sql_quote($_POST['visible']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		public function delete($id) {
			global $mysql;
			
			$this->deleteFlag($id);
			
			$mysql->query("
				DELETE FROM languages
				WHERE id = '". intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}	

		private function deleteFlag($id) {
			global $mysql, $config;
			
			$post = $this->get($id);
			
			if ($post['flag'] != '') {
				@unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['flag']);
			}

			$mysql->query("
				UPDATE languages
				SET flag = ''
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}
	}
?>