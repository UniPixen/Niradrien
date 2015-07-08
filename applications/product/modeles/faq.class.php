<?php
	class faq {
		public function getAll($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products_faqs
				WHERE product_id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			return $return;
		}

		public function add($id) {
			global $mysql, $langArray;

			if (!isset($_POST['question']) || trim($_POST['question']) == '') {
				$error['question'] = $langArray['error_not_set_question'];
			}

			if (!isset($_POST['answer']) || trim($_POST['answer']) == '') {
				$error['answer'] = $langArray['error_not_set_answer'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				INSERT INTO products_faqs (
					product_id,
					member_id,
					question,
					answer,
					datetime
				)

				VALUES (
					'" . intval($id) . "',
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['question']) . "',
					'" . sql_quote($_POST['answer']) . "',
					NOW()
				)
			");

			return true;
		}

		public function delete($id, $productID) {
			global $mysql;

			$mysql->query("
				DELETE FROM products_faqs
				WHERE id = '" . intval($id) . "' AND product_id = '" . intval($productID) . "'
				LIMIT 1
			");

			return true;
		}

		public function CountAll($productID) {
			global $mysql;

			$mysql->query("
				SELECT COUNT(id) as count
				FROM products_faqs
				WHERE product_id = '" . intval($productID) . "'
			");

			$r = $mysql->fetch_array();
			return $r['count'];
		}
	}
?>