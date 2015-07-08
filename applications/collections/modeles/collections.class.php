<?php
	class collections extends base {
		public $membersWhere = '';
		
		public function __construct() {
			$this->uploadFileDirectory = 'collections/';
		}

		public function getAll($start = 0, $limit = 0, $where = '', $byType = false, $order = "datetime DESC") {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM collections
				$where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$this->membersWhere = '';

			while ($d = $mysql->fetch_array()) {
				if ($byType) {
					$return[$d['public']][] = $d;
				}
				else {
					$return[] = $d;
				}

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}
				
				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}
	
		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM collections
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function add() {
			global $mysql;

			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$_POST['name'] = 'Bookmark Collection';
			}

			if (!isset($_POST['description'])) {
				$_POST['description'] = '';
			}

			if (!isset($_POST['publically_visible'])) {
				$_POST['publically_visible'] = 'false';
			}

			else {
				$_POST['publically_visible'] = 'true';
			}

			$photo = $this->upload('file_upload', '', false);

			if (substr($photo, 0, 6) == 'error_') {
				$photo = '';
			}

			$mysql->query("
				INSERT INTO collections (
					member_id,
					name,
					text,
					photo,
					datetime,
					public
				)

				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['description']) . "',
					'" . sql_quote($photo)."',
					NOW(),
					'" . sql_quote($_POST['publically_visible']) . "'
				)
			");

			if ($photo != '') {
				require_once SYSTEM_PATH . '/classes/image.class.php';
				
				$imageClass = new Image();		
				$imageClass->crop(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $photo, 370, 200);
			}

			return $mysql->insert_id();
		}

		public function edit($id) {
			global $mysql;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$_POST['name'] = 'Bookmark Collection';
			}

			if (!isset($_POST['description'])) {
				$_POST['description'] = '';
			}

			if (!isset($_POST['publically_visible'])) {
				$_POST['publically_visible'] = 'false';
			}

			else {
				$_POST['publically_visible'] = 'true';
			}

			$setQuery = '';
			$photo = $this->upload('file_upload', '', false);

			if (substr($photo, 0, 6) == 'error_') {
				$photo = '';
			}

			if ($photo != '') {
				$this->deletePhoto($id);
				$setQuery = " photo = '" . sql_quote($photo) . "', ";
			}

			$mysql->query("
				UPDATE collections
				SET name = '" . sql_quote($_POST['name']) . "',
					text = '" . sql_quote($_POST['description']) . "',
					$setQuery
					public = '" . sql_quote($_POST['publically_visible']) . "'
				WHERE id = '" . intval($id) . "'
			");

			if ($photo != '') {
				require_once SYSTEM_PATH . '/classes/image.class.php';
				
				$imageClass = new Image();
				$imageClass->crop(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $photo, 370, 200);
			}

			return true;
		}

		public function delete($id) {
			global $mysql;

			$this->deletePhoto($id);

			$mysql->query("
				DELETE FROM products_collections
				WHERE collection_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM collections
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		private function deletePhoto($id) {
			global $mysql, $config;

			$post = $this->get($id);
			
			if ($post['photo'] != '') {
				@unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}

			$mysql->query("
				UPDATE collections
				SET photo = ''
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}

		public function isInCollection($id, $collectionID) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products_collections
				WHERE product_id = '" . intval($id) . "' AND collection_id = '" . intval($collectionID) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function bookmark($id) {
			global $mysql;

			if (isset($_POST['name']) && trim($_POST['name']) != '') {
				$collectionID = $this->add();
			}

			if (!isset($collectionID)) {
				if(!isset($_POST['collection_id']) || !is_numeric($_POST['collection_id'])) {
					$collectionID = 0;
				}

				else {
					$collectionID = $_POST['collection_id'];
				}
			}

			if ($this->isInCollection($id, $collectionID)) {
				return true;
			}

			$mysql->query("
				INSERT INTO products_collections (
					product_id,
					collection_id
				)

				VALUES (
					'" . intval($id) . "',
					'" . intval($collectionID) . "'
				)
			");

			$this->incCollection($collectionID, '+');
			return true;
		}

		public function deleteBookmark($collectionID, $productID) {
			global $mysql;

			$mysql->query("
				DELETE FROM products_collections
				WHERE collection_id = '" . intval($collectionID) . "' AND product_id = '" . intval($productID) . "'
			");

			if ($mysql->affected_rows() > 0) {
				$this->incCollection($collectionID, '-');
			}

			return false;
		}

		public function incCollection($collectionID, $sign = '+') {
			global $mysql;

			$mysql->query("
				UPDATE collections
				SET products = products $sign 1
				WHERE id = '" . intval($collectionID) . "'
			");

			return true;
		}

		public function getProducts($collectionID, $start = 0, $limit = 0, $where = '', $order = 'datetime ASC', $public = false) {
			global $mysql;

			if ($public) {
				$row = $this->get($collectionID);
				if ($row['public'] == 'false') {
					return false;
				}
			}

			$mysql->query("
				SELECT *
				FROM products_collections
				WHERE collection_id = '" . intval($collectionID) . "'			
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';

			while ($d = $mysql->fetch_array()) {
				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " id = '" . intval($d['product_id']) . "' ";
			}

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start,$limit ";
			}

			$mysql->query("
				SELECT *
				FROM products
				WHERE ($whereQuery) $where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$this->membersWhere = '';
			$return = array();

			while ($d = $mysql->fetch_array()) {
				$categories = explode('|', $d['categories']);
				unset($d['categories']);
				$d['categories'] = array();
				$row = 0;

				foreach($categories AS $cat) {
					$categories1 = explode(',', $cat);
					
					foreach ($categories1 as $c) {
						$c = trim($c);
						if ($c != '') {
							$d['categories'][$row][$c] = $c;
						}
					}

					$row++;
				}

				$return[$d['id']] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}

				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function isRate($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM collections_rates
				WHERE collection_id = '" . intval($id) . "' AND member_id = '" . intval($_SESSION['member']['member_id']) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function rate($id, $rate) {
			global $mysql, $collection;

			$row = $this->isRate($id);

			if (is_array($row)) {
				return $collection;
			}

			$collection['votes'] = $collection['votes'] + 1;
			$collection['score'] = $collection['score'] + $rate;
			$collection['rating'] = $collection['score'] / $collection['votes'];
			$collection['rating'] = round($collection['rating']);

			$mysql->query("
				UPDATE collections
				SET rating = '" . intval($collection['rating']) . "',
					score = '" . intval($collection['score']) . "',
					votes = '" . intval($collection['votes']) . "'
				WHERE id = '" . intval($id) . "'
			");

			$mysql->query("
				INSERT INTO collections_rates (
					collection_id,
					member_id,
					rate,
					datetime
				)

				VALUES (
					'" . intval($id) . "',
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . intval($rate) . "',
					NOW()
				)
			");

			return $collection;
		}
	}
?>