<?php
	class groups extends base {
		function __construct() {
		}

		public function getAll($start = 0, $limit = 0) {
			global $mysql;

			$limitQuery = "";
			if ($limit != 0) {
				$limitQuery = "LIMIT $start, $limit";
			}

			$return = $mysql->getAll("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM members_groups
				ORDER BY name ASC
				$limitQuery
			");

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}
	
		public function get($id) {
			global $mysql;
			
			$return = $mysql->getRow("
				SELECT *
				FROM members_groups
				WHERE ug_id = '" . intval($id) . "'
			");
			
			$rights = json_decode($return['rights'], true);
			if (is_array($rights)) {
				foreach($rights as $k => $v) {
					$return['applications'][$k] = $v;
				}
			}
			
			return $return;
		}
	
		public function add() {
			global $mysql, $langArray;

			if (!isset($_POST['name']) || strlen(trim($_POST['name'])) < 1) {
				$error['name'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['description']) || strlen(trim($_POST['description'])) < 1) {
				$error['desc'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['applications'])) {
				$error['applications'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}
			
			$rights = array();		
			if (is_array($_POST['applications'])) {
				foreach ($_POST['applications'] as $k => $v) {
					$rights[$k] = $v;
				}
			}
			
			$mysql->query("
				INSERT INTO members_groups (
					name,
					description,
					rights
				)
				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['description']) . "',
					'" . json_encode($rights) . "'
				)
			", __FUNCTION__ );
			
			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;

			if (!isset($_POST['name']) || strlen(trim($_POST['name'])) < 1) {
				$error['name'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['description']) || strlen(trim($_POST['description'])) < 1) {
				$error['desc'] = $langArray['error_fill_this_field'];
			}
			
			if (!isset($_POST['applications'])) {
				$error['applications'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}
			
			$rights = array();		
			if (is_array($_POST['applications'])) {
				foreach($_POST['applications'] as $k => $v) {
					$rights[$k] = $v;
				}
			}
			
			$mysql->query("
				UPDATE members_groups 
				SET name = '" . sql_quote($_POST['name']) . "',
					description = '" . sql_quote($_POST['description']) . "',
					rights = '" . json_encode($rights) . "'
				WHERE ug_id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}

		public function delete($id) {
			global $mysql;

			$mysql->query("
				DELETE FROM members_groups
				WHERE ug_id = '" . intval($id) . "'
				LIMIT 1
			", __FUNCTION__ );
			
			return true;
		}
	}