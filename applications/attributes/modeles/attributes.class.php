<?php
	class attributes extends base {
		function __construct() {
			$this->tableName = 'attributes';
			$this->uploadFileDirectory = 'attributes/';
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
				FROM attributes
				$where
				ORDER BY order_index ASC
				$limitQuery
				", __FUNCTION__
			);

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllCategories($start = 0, $limit = 0, $where = '') {
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
				FROM attributes_categories
				$where
				ORDER BY order_index ASC
				$limitQuery
				", __FUNCTION__
			);

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

		public function getAllWithCategories($where = '') {
			global $mysql;

			$whereQuery = '';
			
			if ($where != '') {
				$whereQuery = " WHERE " . $where;
			}

			$mysql->query("
				SELECT *
				FROM attributes_categories
				$whereQuery
				ORDER BY order_index ASC
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';
			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " category_id = '" . intval($d['id']) . "' ";
			}

			if ($whereQuery != '') {
				$mysql->query("
					SELECT *
					FROM attributes
					WHERE $whereQuery
					ORDER BY order_index ASC
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$return[$d['category_id']]['attributes'][] = $d;
					}
				}
			}

			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM attributes
				WHERE id = '" . intval($id) . "'
				", __FUNCTION__
			);

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function add() {
			global $mysql, $langArray, $config;

			if (!isset($_POST['category_id']) || !is_numeric($_POST['category_id'])) {
				$error['category'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['name_en']) || trim($_POST['name_en']) == '') {
				$error['name_en'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['tooltip']) || trim($_POST['tooltip']) == '') {
				$error['tooltip'] = $langArray['error_fill_this_field'];
			}

			$photo = $this->upload('photo', '', false);

			if (substr($photo, 0, 6) == 'error_') {
				$error['photo'] = $langArray[$photo];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}

			$this->orderWhere = " AND category_id = '" . intval($_POST['category_id']) . "' ";
			$orderIndex = $this->getNextOrderIndex();

			$mysql->query("
				INSERT INTO attributes (
					category_id,
					name,
					name_en,
					tooltip,
					photo,
					visible,
					order_index
				)
				VALUES (
					'" . intval($_POST['category_id']) . "',
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['name_en']) . "',
					'" . sql_quote($_POST['tooltip']) . "',
					'" . sql_quote($photo) . "',
					'" . sql_quote($_POST['visible']) . "',
					'" . intval($orderIndex) . "'
				)
				", __FUNCTION__
			);

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

			if (!isset($_POST['name_en']) || trim($_POST['name_en']) == '') {
				$error['name_en'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['tooltip']) || trim($_POST['tooltip']) == '') {
				$error['tooltip'] = $langArray['error_fill_this_field'];
			}

			$photo = $this->upload('photo', '', false);

			if (substr($photo, 0, 6) == 'error_') {
				$error['photo'] = $langArray[$photo];
			}

			if (isset($error)) {
				return $error;
			}

			$setQuery = '';

			if ($photo != '' || isset($_POST['deletePhoto'])) {
				$this->deletePhoto($id);
			}

			if ($photo != '') {
				$setQuery .= " photo = '" . sql_quote($photo) . "', ";
			}

			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}

			$mysql->query("
				UPDATE attributes
				SET name = '" . sql_quote($_POST['name']) . "',
				name_en = '" . sql_quote($_POST['name_en']) . "',
				tooltip = '" . sql_quote($_POST['tooltip']) . "',
				$setQuery
				visible = '" . sql_quote($_POST['visible']) . "'
				WHERE id = '" . intval($id) . "'
				", __FUNCTION__
			);

			return true;
		}

		public function delete($id) {
			global $mysql;

			$this->deletePhoto($id);
			
			$mysql->query("
				DELETE FROM attributes
				WHERE id = '" . intval($id) . "'
				", __FUNCTION__
			);

			$mysql->query("
				DELETE FROM products_attributes
				WHERE attribute_id = '" . intval($id) . "'
				", __FUNCTION__
			);

			return true;
		}

		private function deletePhoto($id) {
			global $mysql, $config;

			$post = $this->get($id);
			if ($post['photo'] != '') {
				@unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}

			$mysql->query("
				UPDATE attributes
				SET photo = ''
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}
	}
?>