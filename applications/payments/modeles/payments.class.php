<?php
	class payments extends base {
		function __construct() {
			$this->tableName = 'payments';
			$this->uploadFileDirectory = 'payments/';
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
				FROM payments
				$where
				ORDER BY order_index ASC
				$limitQuery
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			
			while($d = $mysql->fetch_array()) {
				$return[$d['name']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($name) {
			global $mysql, $langArray;

			$return = $mysql->getRow("
				SELECT *
				FROM payments
				WHERE name = '" . sql_quote($name) . "'
			" );

			return $return;
		}

		public function getByID($id) {
			global $mysql, $langArray;

			$return = $mysql->getRow("
				SELECT *
				FROM payments
				WHERE id = '" . sql_quote($id) . "'
			" );

			return $return;
		}

		public function edit($id) {
			global $mysql, $langArray;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['merchant_id']) || trim($_POST['merchant_id']) == '') {
				$error['merchant_id'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			$logo = $this->upload('photo', '', false);
			if (substr($logo, 0, 6) == 'error_') {
				$error['logo'] = $langArray[$logo];
			}

			if (isset($error)) {
				return $error;
			}

			$setQuery = '';
			if ($logo != '' || isset($_POST['deletePhoto'])) {
				$this->deletePhoto($id);
			}

			if ($logo != '') {
				$setQuery .= " logo = '" . sql_quote($logo) . "', ";
			}

			if (!isset($_POST['status'])) {
				$_POST['status'] = '0';
			}

			// SI ON ENTRE RIEN, LA VALEUR DEVIENT NULL
			$minAmount = ($_POST['minimum_amount']) ? intval($_POST['minimum_amount']) : 'DEFAULT';
			$maxAmount = ($_POST['maximum_amount']) ? intval($_POST['maximum_amount']) : 'DEFAULT';

			if ($minAmount == 'DEFAULT' && $maxAmount > 0) {
				$minAmount = 0;
			}

			$mysql->query("
				UPDATE payments
				SET name = '" . sql_quote($_POST['name']) . "',
					sandbox = '" . sql_quote($_POST['sandbox']) . "',
					merchant_id = '" . sql_quote($_POST['merchant_id']) . "',
					token = '" . sql_quote($_POST['token']) . "',
					minimum_amount = " . $minAmount . ",
					maximum_amount = " . $maxAmount . ",
					purchase = '" . sql_quote($_POST['purchase']) . "',
					deposit = '" . sql_quote($_POST['deposit']) . "',
					$setQuery
					status = '" . sql_quote($_POST['status']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		private function deletePhoto($id) {
			global $mysql, $config;
			
			$post = $this->getByID($id);
			
			if ($post['photo'] != '') {
				unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}

			$mysql->query("
				UPDATE payments
				SET photo = ''
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}
	}
?>