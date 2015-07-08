<?php
	class team extends base {
		function __construct() {
			global $config;

			$this->tableName = 'team';
			$this->photoSizes = $config['team_photo_sizes'];
			$this->uploadFileDirectory = 'team/';
		}

		public function getAll($start = 0, $limit = 0, $where = '', $order = 'order_index ASC') {
			global $mysql;

			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = "LIMIT $start, $limit";
			}

			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$a = $mysql->query("
				SELECT *
				FROM team
				INNER JOIN members ON team.member_id = members.member_id
				$where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array($a)) {
				$return[$d['member_id']] = $d;

				$b = $mysql->query("
					SELECT *
					FROM countries
					WHERE id = '" . intval($d['country_id']) . "'
				");

				$return[$d['member_id']]['country'] = $mysql->fetch_array($b);
			}

			$this->foundRows = $mysql->getFoundRows();

			return $return;
		}

		public function get($id) {
			global $mysql, $langArray;

			$return = $mysql->getRow("
				SELECT *
				FROM team
				INNER JOIN members ON team.member_id = members.member_id && members.member_id = '" . intval($id) . "'
			" );

			$mysql->query("
				SELECT *
				FROM countries
				WHERE id = '" . intval($return['country_id']) . "'
			");

			$return['country'] = $mysql->fetch_array();

			return $return;
		}

		public function add() {
			global $mysql, $langArray, $config;

			if (!isset($_POST['member_id']) || trim($_POST['member_id']) == '') {
				$error['member_id'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['role']) || trim($_POST['role']) == '') {
				$error['role'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['role_en']) || trim($_POST['role_en']) == '') {
				$error['role_en'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}
			
			$photo = $this->upload('photo', '', false);

			if (substr($photo, 0, 6) == 'error_') {
				$error['photo'] = $langArray[$photo];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				INSERT INTO team (
					member_id,
					role,
					role_en,
					photo
				)

				VALUES (
					'" . intval($_POST['member_id']) . "',
					'" . sql_quote($_POST['role']) . "',
					'" . sql_quote($_POST['role_en']) . "',
					'" . sql_quote($photo) . "'
				)
			", __FUNCTION__ );

			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;

			if (!isset($_POST['role']) || trim($_POST['role']) == '') {
				$error['role'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['role_en']) || trim($_POST['role_en']) == '') {
				$error['role_en'] = $langArray['error_fill_this_field'];
			}

			$photo = $this->upload('photo', '', false, true, htmlentities($_POST['firstname']));

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

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE team
				SET role = '" . sql_quote($_POST['role']) . "',
					$setQuery
					role_en = '" . sql_quote($_POST['role_en']) . "'
				WHERE member_id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		public function delete($id) {
			global $mysql;
			
			$this->deletePhoto($id);

			$mysql->query("
				DELETE FROM team
				WHERE member_id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		private function deletePhoto($id) {
			global $mysql, $config;

			$post = $this->get($id);

			if ($post['photo'] != '') {
				unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}

			$mysql->query("
				UPDATE team
				SET photo = ''
				WHERE member_id = '" . intval($id) . "'
			");

			return true;
		}
	}
?>