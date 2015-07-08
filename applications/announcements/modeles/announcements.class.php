<?php
	class announcements extends base {
		function __construct() {
			$this->tableName = 'announcements';
			$this->uploadFileDirectory = 'announcements/';
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
				FROM announcements
				$where
				ORDER BY id ASC
				$limitQuery
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

		public function getSystemAnnouncements($start = 0, $limit = 0, $where = '') {
			global $mysql, $language;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM announcements
				WHERE visible = 'true' && type = 'system'
				ORDER BY id ASC
				$limitQuery
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

		public function getAuthorAnnouncements($start = 0, $limit = 0, $where = '') {
			global $mysql, $language;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM announcements
				WHERE visible = 'true' && type = 'authors'
				ORDER BY datetime DESC
				$limitQuery
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
				FROM announcements
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

			if (!isset($_POST['message']) || trim($_POST['message']) == '') {
				$error['message'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				INSERT INTO announcements (
					name,
					message,
					datetime,
					url,
					photo,
					type,
					visible
				)

				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['message']) . "',
					NOW(),
					'',
					'',
					'authors',
					'" . intval($_POST['visible']) . "'
				)
			", __FUNCTION__ );

			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;

			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if ($_POST['type'] == 'authors') {
				if (!isset($_POST['message']) || trim($_POST['message']) == '') {
					$error['message'] = $langArray['error_fill_this_field'];
				}

				if (!isset($_POST['datetime']) || trim($_POST['datetime']) == '') {
					$error['datetime'] = $langArray['error_fill_this_field'];
				}
			}

			else {
				if (!isset($_POST['url']) || trim($_POST['url']) == '') {
					$error['url'] = $langArray['error_fill_this_field'];
				}

				$photo = $this->upload('photo', '', false);

				if (substr($photo, 0, 6) == 'error_') {
					$error['photo'] = $langArray[$photo];
				}

				$setQuery = '';

				if ($photo != '' || isset($_POST['deletePhoto'])) {
					$this->deletePhoto($id);
				}

				if ($photo != '') {
					$setQuery .= " photo = '" . sql_quote($photo) . "', ";
				}
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}

			if ($_POST['type'] == 'authors') {
				$mysql->query("
					UPDATE announcements
					SET name = '" . sql_quote($_POST['name']) . "',
						message = '" . sql_quote($_POST['message']) . "',
						datetime = '" . sql_quote($_POST['datetime']) . "',
						visible = '" . sql_quote($_POST['visible']) . "'
					WHERE id = '" . intval($id) . "'
				", __FUNCTION__ );
			}

			else {
				$mysql->query("
					UPDATE announcements
					SET name = '" . sql_quote($_POST['name']) . "',
						url = '" . sql_quote($_POST['url']) . "',
						$setQuery
						visible = '" . sql_quote($_POST['visible']) . "'
					WHERE id = '" . intval($id) . "'
				", __FUNCTION__ );
			}

			return true;
		}

		public function delete($id) {
			global $mysql;
			$this->deletePhoto($id);

			$mysql->query("
				DELETE FROM announcements
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		private function deletePhoto($id) {
			global $mysql, $config;

			$post = $this->get($id);
			
			if ($post['photo'] != '') {
				@unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}

			$mysql->query("
				UPDATE announcements
				SET photo = ''
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}
	}
?>