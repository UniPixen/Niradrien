<?php
	class category extends base {
		function __construct() {
			$this->tableName = 'categories';
		}

		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql;
			
			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}
			
			if ($where != '') {
				$where = "WHERE " . $where;
			}
			
			$mysql->query("
				SELECT *
				FROM categories
				$where
				ORDER BY order_index ASC
				$limitQuery
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			$whereQuery = '';
			while ($d = $mysql->fetch_array()) {
				//$d['clear_text'] = trim(strip_tags($d['text']));

				// if ($d['sub_of'] > 0) {
				// 	$d['keywords'] = $this->subCategoriesKeywords($d['id']);
				// }

				$return[$d['id']] = $d;
			}
			
			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}
		
		public function get($id) {
			global $mysql, $language;
			
			$mysql->query("
				SELECT *
				FROM categories
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$d = $mysql->fetch_array();
			//$d['clear_text'] = trim(strip_tags($d['text']));
			return $d;
		}

		public function getByKeyword($keyword) {
			global $mysql, $language;
			
			$mysql->query("
				SELECT *
				FROM categories
				WHERE keywords = '" . sql_quote($keyword) . "'
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$d = $mysql->fetch_array();
			
			if ($d['sub_of'] > 0) {
				$d['subcategory'] = true;
			}
			else {
				$d['subcategory'] = false;
			}

			//$d['clear_text'] = trim(strip_tags($d['text']));
			return $d;
		}

		public function getIDByKeyword($keyword) {
			global $mysql, $language;
			
			$mysql->query("
				SELECT id
				FROM categories
				WHERE keywords = '" . sql_quote($keyword) . "' && sub_of = 0
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$d = $mysql->fetch_array();

			return $d['id'];
		}

		public function getAllCategoriesPricesMinMax() {
			global $mysql;

			$mysql->query("
				SELECT MAX(price) AS price_max, MIN(price) AS price_min
				FROM products
				WHERE status = 'active';
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$d = $mysql->fetch_array();

			return $d;
		}

		public function getCategoryPricesMinMax($categoryID) {
			global $mysql;

			$mysql->query("
				SELECT MAX(price) AS price_max, MIN(price) AS price_min
				FROM products
				WHERE status = 'active' && categories LIKE '%," . intval($categoryID) . ",%';
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$d = $mysql->fetch_array();

			return $d;
		}

		public function add() {
			global $mysql, $langArray;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$parentID = 0;
			
			$this->orderWhere = " AND sub_of = '" . intval($_GET['sub_of']) . "' ";
			$orderIndex = $this->getNextOrderIndex();

			if (!isset($_POST['name_en']) || $_POST['name_en'] == '') {
				$_POST['name_en'] = $_POST['name'];
			}
			
			if (!isset($_POST['title']) || $_POST['title'] == '') {
				$_POST['title'] = $_POST['name'];
			}
			
			if (!isset($_POST['keywords']) || $_POST['keywords'] == '') {
				$_POST['keywords'] = $_POST['name'];
			}
			
			if (!isset($_POST['description']) || $_POST['description'] == '') {
				$_POST['description'] = $_POST['name'];
			}

			if (!isset($_POST['description_en']) || $_POST['description_en'] == '') {
				$_POST['description_en'] = $_POST['name'];
			}
			
			$mysql->query("
				INSERT INTO categories (
					sub_of,
					title,
					keywords,
					description,
					description_en,
					name,
					name_en,
					visible,
					order_index
				)
				VALUES (
					'" . intval($_GET['sub_of']) . "',
					'" . sql_quote($_POST['title']) . "',
					'" . sql_quote($_POST['keywords']) . "',
					'" . sql_quote($_POST['description']) . "',
					'" . sql_quote($_POST['description_en']) . "',
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['name_en']) . "',
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
			
			if (!isset($_POST['sub_of']) || !is_numeric($_POST['sub_of'])) {
				$error['sub'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$setQuery = '';
			if ($_POST['sub_of'] != $_POST['sub_of_old']) {
				$info = $this->get($id);
				$this->orderWhere = " AND sub_of = '" . intval($_POST['sub_of']) . "' ";
				$orderIndex = $this->getNextOrderIndex();
				$setQuery .= " order_index = '" . intval($orderIndex) . "', ";
			}

			if (!isset($_POST['name_en'])) {
				$_POST['name_en'] = '';
			}
			
			if (!isset($_POST['title'])) {
				$_POST['title'] = '';
			}
			
			if (!isset($_POST['keywords'])) {
				$_POST['keywords'] = '';
			}
			
			if (!isset($_POST['description'])) {
				$_POST['description'] = '';
			}

			if (!isset($_POST['description_en'])) {
				$_POST['description_en'] = '';
			}
			
			$mysql->query("
				UPDATE categories 
				SET	sub_of = '" . intval($_POST['sub_of']) . "',
					title = '" . sql_quote($_POST['title']) . "',
					keywords = '" . sql_quote($_POST['keywords']) . "',
					description = '" . sql_quote($_POST['description']) . "',
					description_en = '" . sql_quote($_POST['description_en']) . "',
					name = '" . sql_quote($_POST['name']) . "',
					name_en = '" . sql_quote($_POST['name_en']) . "',
					$setQuery						
					visible = '" . sql_quote($_POST['visible']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		public function delete($id) {
			global $mysql;
			
			// $info = $this->get($id);
			
			// $this->orderWhere = " AND sub_of = '" . intval($info['sub_of']) . "' ";
			// $orderIndex = $this->getNextOrderIndex();
			
			// $mysql->query("
			// 	UPDATE categories
			// 	SET sub_of = '0',
			// 		order_index = order_index + '" . intval($orderIndex) . "'
			// 	WHERE sub_of = '" . intval($id) . "'
			// ");
			
			$mysql->query("
				DELETE FROM categories
				WHERE id = '" . intval($id) . "' || sub_of = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}
		
		public function getAllWithChilds($id = 0, $where = '') {
			global $mysql;
			
			$whereQuery = '';
			if ($id != 0) {
				$whereQuery = " WHERE id <> '" . intval($id) . "' ";
			}

			elseif ($where!='') {
				$whereQuery = " WHERE " . $where;
			}
			
			$mysql->query("
				SELECT *
				FROM categories
				$whereQuery
				ORDER BY order_index ASC
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			
			while($d = $mysql->fetch_array()) {
				$return[$d['sub_of']][$d['id']] = $d;
			}
			
			return $return;
		}

		public function getAllWithChildsByKeyword($keywords = 0, $where = '') {
			global $mysql;
			
			$whereQuery = '';
			if (!is_null($keywords)) {
				$whereQuery = " WHERE keywords <> '" . sql_quote($keywords) . "' ";
			}

			elseif ($where!='') {
				$whereQuery = " WHERE " . $where;
			}
			
			$mysql->query("
				SELECT *
				FROM categories
				$whereQuery
				ORDER BY order_index ASC
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			
			while($d = $mysql->fetch_array()) {
				$return[$d['sub_of']][$d['id']] = $d;
			}
			
			return $return;
		}

		public function generateSelect($array, $selected = 0, $subOf = 0, $depth = 0) {
			$text = '';
			
			if (isset($array[$subOf])) {
				foreach($array[$subOf] as $v) {
					$text .= '<option value="' . $v['id'] . '"';
					
					if (is_array($selected)) { 
						if (in_array($v['id'], $selected)) {
							$text .= ' selected="selected" ';
						}
					}
					else {
						if ($v['id'] == $selected) {
							$text .= ' selected="selected" ';
						}
					}
					$text .= '>';
					
					if ($depth > 0) {
						for($i = 0; $i < $depth; $i++) {
							$text .= '&nbsp;&nbsp;&nbsp;';
						}
					}
					
					$text .= ' - '.$v['name'].'</option>';
					$text .= $this->generateSelect($array, $selected, $v['id'], $depth + 1);
				}
			}

			return $text;
		}
		
		public function generateList($array, $subOf = 0, $depth = 0) {
			$text = '';
			
			if (isset($array[$subOf])) {
				if ($depth > 0) {
					$text .= '<ul class="category-tree" style="float: left; width: 220px;">';
				}
				
				foreach($array[$subOf] as $v) {
					if ($depth == 0) {
						$text .= '<ul class="category-tree" style="float: left; width: 220px;">';
					}
				
					$text .= '<li><a href="/category/' . $v['id'] . '/' . url($v['name']) . '">' . $v['name'] . '</a>';
					$text .= $this->generateList($array, $v['id'], $depth + 1);
					$text .= '</li>';

					if ($depth == 0) {
						$text .= '</ul>';
					}
				}
				
				if ($depth > 0) {
					$text .= '</ul>';
				}
			}
			
			return $text;
		}
		
		public function generatebrowseList($array, $subOf = 0, $depth = 0) {
			$text = '';
			
			if (isset($array[$subOf])) {
				if ($depth > 0) {
					$text .= '<ul>';
				}
				
				foreach($array[$subOf] as $v) {
					if ($depth == 0) {
						
					}

					$text .= '<li><a href="/category/' . $this->categoryParentsPath($v['id']) . '">' . $v['name'] . '</a>';
					$text .= $this->generatebrowseList($array, $v['id'], $depth + 1);
					$text .= '</li>';

					if ($depth == 0) {
						
					}
				}
				
				if ($depth > 0) {
					$text .= '</ul>';
				}
			}
			
			return $text;
		}

		public function getCategoryParents($categories, $categoryID) {
			$return = '';

			if (isset($categories[$categoryID])) {
				$return .= $categoryID . ',';
				$return .= $this->getCategoryParents($categories, $categories[$categoryID]['sub_of']);
			}
			
			return $return;
		}

		function categoryParentsPath($categoryID) {
			global $mysql;

		    if (!isset($categoryID)) {
		        return false;
		    }

			$whereQuery = '';
			if (isset($categoryID)) {
				$whereQuery = " WHERE id = " . intval($categoryID);
			}

			elseif ($where!='') {
				$whereQuery = " WHERE " . $where;
			}
			
			$mysql->query("
				SELECT *
				FROM categories
				$whereQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$link = '/';

			while($d = $mysql->fetch_array()) {
				$mysql->query("
					SELECT *
					FROM categories
					WHERE id = ". $d['sub_of'] ."
				");

				$return[$d['id']] = $d; // Tout l'ID et son sous-id

				if ($return[$d['id']]['sub_of'] == 0) {
					$link .= '/' . $return[$d['id']]['keywords'];
				}

				if ($return[$d['id']]['sub_of'] > 0) {
					$link .= '/' . $return[$return[$d['id']]['id']]['keywords'];
				}
			}

			$return[$d['id']]['url'] = $link;
			$link = explode('/', $link);
			array_shift($link);
			$link = array_reverse($link);
			$link = substr(implode('/', $link), 0, -1);
			
			return $link;
		}
	}
?>