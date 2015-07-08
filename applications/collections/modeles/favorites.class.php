<?php
	class favorites extends base {
		public $membersWhere = '';

		public function getAll($start = 0, $limit = 0, $where = '', $order = "datetime DESC") {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT *
				FROM favorites
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
				$return[] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}
				
				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}
	
		public function get($productID) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM favorites
				WHERE product_id = '" . intval($productID) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function isInFavorites($productID, $memberID) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM favorites
				WHERE product_id = " . intval($productID) . " AND member_id = " . intval($memberID) . "
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function getProducts($memberID, $start = 0, $limit = 0, $where = '', $order = 'fav.datetime DESC') {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM favorites
				WHERE member_id = " . intval($memberID) . "
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';

			while ($d = $mysql->fetch_array()) {
				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " fav.member_id = " . intval($d['member_id']);
			}

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT *
				FROM products
				LEFT JOIN favorites AS fav ON fav.product_id = products.id  
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
					foreach($categories1 as $c) {
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

		public function add($productID, $memberID) {
			global $mysql;

			if ($this->isInFavorites($productID, $memberID)) {
				return false;
			}

			$mysql->query("
				INSERT INTO favorites (
					member_id,
					product_id,
					datetime
				)

				VALUES (
					'" . intval($memberID) . "',
					'" . intval($productID) . "',
					NOW()
				)
			");

			return true;
		}

		public function delete($productID, $memberID) {
			global $mysql;

			if (!$this->isInFavorites($productID, $memberID)) {
				return false;
			}

			$mysql->query("
				DELETE FROM favorites
				WHERE member_id = '" . intval($memberID) . "' && product_id = '" . intval($productID) . "'
			");

			return true;
		}
	}
?>