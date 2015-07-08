<?php 
	class support {
		public $foundRows = null;

		function __construct() {
			$this->tableName = 'support';
		}

		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql, $language, $langArray;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM support
				$where
				ORDER BY answer_datetime ASC, datetime DESC
				$limitQuery
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql, $language;

			$mysql->query("
				SELECT *
				FROM support
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function add() {
			global $mysql, $langArray, $categories;

			if (!isset($_POST['username']) || trim($_POST['username']) == '') {
				$error['username'] = $langArray['error_not_set_name'];
			}

			if (!isset($_POST['email']) || !check_email($_POST['email'])) {
				$error['email'] = $langArray['error_invalid_email'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['issue_id'])) {
				$_POST['issue_id'] = 0;
			}

			$issue = '';

			if (isset($categories[$_POST['issue_id']])) {
				$issue = $categories[$_POST['issue_id']]['name'];
			}

			$text = $langArray['username'] . ': ' . $_POST['username'] . ' ' . $langArray['email'] . ': ' . $_POST['email'] . ' ' . $langArray['issue'] . ': ' . $issue . ' ' . $langArray['description_of_issue'] . ': ' . $_POST['issue_details'] . ' ';		

			$mysql->query("
				INSERT INTO support (
					name,
					email,
					issue,
					issue_id,
					short_text,
					datetime
				)

				VALUES (
					'" . sql_quote($_POST['username']) . "',
					'" . sql_quote($_POST['email']) . "',
					'" . sql_quote($issue) . "',
					'" . intval($_POST['issue_id']) . "',
					'" . sql_quote($text) . "',
					NOW()
				)
			", __FUNCTION__ );

			// Envoyer l'email
			$mysql->query("
				SELECT *
				FROM system
				WHERE key = 'admin_mail' OR key = 'contact_mail'
			");

			while($d = $mysql->fetch_array()) {
				if ($d['key'] == 'contact_mail') {
					$sendTo = $d['value'];
					break;
				}

				$sendTo = $d['value'];
			}

			require_once SYSTEM_PATH . '/classes/email.class.php';
			$emailClass = new email();
			$emailClass->to($sendTo);
			$emailClass->fromEmail = $_POST['email'];
			$emailClass->contentType = 'text/plain';
			$emailClass->subject = 'Contact form';
			$emailClass->message = $text;
			$emailClass->send();

			unset($emailClass);
			return true;
		}

		public function delete($id) {
			global $mysql;

			$mysql->query("
				DELETE FROM support
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );

			return true;
		}

		public function sendAnswer() {
			global $mysql, $langArray, $data;
			
			if (!isset($_POST['answer']) || trim($_POST['answer']) == '') {
				$error['answer'] = $langArray['error_not_set_name'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE support
				SET answer = '" . sql_quote($_POST['answer']) . "',
					answer_datetime = NOW()
				WHERE id = '" . intval($data['id']) . "'
				LIMIT 1
			", __FUNCTION__ );

			// Envoyer l'email
			$emailClass = new email();
			$emailClass->to($data['email']);
			$emailClass->fromEmail = 'no-reply@'.DOMAIN;
			$emailClass->contentType = 'text/plain';
			$emailClass->subject = '[' . DOMAIN . '] Contact form';
			$emailClass->message = $_POST['answer'] . ' ' . $data['name'] . ' ' . $langArray['wrote'] . ' ============= ' . $data['short_text'];
			$emailClass->send();

			unset($emailClass);
			return true;
		}
	}
?>