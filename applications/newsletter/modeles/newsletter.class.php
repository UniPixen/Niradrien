<?php
	class newsletter extends base {
		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql, $language, $langArray;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM newsletter
				WHERE 1 = 1 $where
				ORDER BY datetime DESC
				$limitQuery
			", __FUNCTION__);

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllEmails($start = 0, $limit = 0) {
			global $mysql, $language, $langArray;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM newsletter_emails
				ORDER BY email ASC
				$limitQuery
			", __FUNCTION__);

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql, $language;

			$mysql->query("
				SELECT *
				FROM newsletter
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

			if (!isset($_POST['text']) || trim($_POST['text']) == '') {
				$error['text'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['send_to']) || trim($_POST['send_to']) == '') {
				$error['send_to'] = $langArray['error_fill_this_field'];
			}

			if (isset($_POST['send_to']) && $_POST['send_to'] == 'city' && (!isset($_POST['city_id']) || !is_numeric($_POST['city_id']))) {
				$error['city'] = $langArray['error_fill_this_field'];
			}

			if (isset($_POST['send_to']) && $_POST['send_to'] == 'group' && (!isset($_POST['bgroup_id']) || !is_numeric($_POST['bgroup_id']))) {
				$error['group'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			$sendID = 0;

			if ($_POST['send_to'] == 'city') {
				$sendID = $_POST['city_id'];
			}

			elseif ($_POST['send_to'] == 'group') {
				$sendID = $_POST['bgroup_id'];
			}

			$mysql->query("
				INSERT INTO newsletter (
					name,
					text,
					datetime,
					send_to,
					send_id
				)

				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['text']) . "',
					NOW(),
					'" . sql_quote($_POST['send_to']) . "',
					'" . intval($sendID) . "'
				)
			", __FUNCTION__ );

			$newsletterID = $mysql->insert_id();

			if ($_POST['send_to'] == 'city') {
				$mysql->query("
					SELECT *
					FROM members
					WHERE city_id = '" . intval($_POST['city_id']) . "' AND newsletter_subscribe = 'true'
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$emails[] = $d['email'];
					}
				}
			}

			if ($_POST['send_to'] == 'group') {
				$mysql->query("
					SELECT u.*
					FROM newsletter_members AS bg
					JOIN members AS u
					ON u.member_id = bg.member_id AND u.newsletter_subscribe = 'true'
					WHERE bg.newslettergroup_id = '" . intval($_POST['bgroup_id']) . "'
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$emails[] = $d['email'];
					}
				}
			}

			if ($_POST['send_to'] == 'active') {
				$mysql->query("
					SELECT *
					FROM members
					WHERE newsletter_subscribe = 'true'
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$emails[] = $d['email'];
					}
				}
			}

			if ($_POST['send_to'] == 'admins') {
				$mysql->query("
					SELECT *
					FROM admins
					WHERE newsletter_subscribe = 'true'
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$emails[] = $d['email'];
					}
				}
			}

			if ($_POST['send_to'] == 'site') {
				$mysql->query("
					SELECT *
					FROM newsletter_emails
					WHERE newsletter_subscribe = 'true'
				");

				if ($mysql->num_rows() > 0) {
					while ($d = $mysql->fetch_array()) {
						$emails[] = $d['email'];
					}
				}
			}

			$mysql->query("
				SELECT *
				FROM newsletter_template
				ORDER BY id DESC
				LIMIT 1
			");

			if ($mysql->num_rows() > 0) {
				$template = $mysql->fetch_array();
				$template = $template['template'];
			}

			else {
				$template = '{$CONTENT}';
			}

			if (isset($emails)) {
				require_once $config['system_path'] . 'classes/email.class.php';

				foreach($emails as $email) {
					$mail = new email();
					$mail->fromEmail = 'no-reply@' . $config['domain'];
					$mail->to($email);
					$mail->subject = $_POST['name'];
					$mail->contentType = 'text/html';
					
					$mail->message = langMessageReplace($template, array(
						'DOMAIN' => $config['domain'],
						'NEWSLETTERID' => $newsletterID,
						'EMAIL' => $email,
						'CONTENT' => $_POST['text']
					));
					
					$mail->send();
					
					unset($mail);
				}
			}

			return true;
		}

		public function incRead($id) {
			global $mysql;

			$mysql->query("
				UPDATE newsletter
				SET readed = readed + 1
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function deleteEmail($email) {
			global $mysql;
			
			$mysql->query("
				UPDATE newsletter_emails
				SET newsletter_subscribe = 'false'
				WHERE email = '" . sql_quote($email) . "'
			");

			return true;
		}

		public function deleteSEmail($id) {
			global $mysql;

			$mysql->query("
				DELETE FROM newsletter_emails
				WHERE id = '" . intval($id) . "'
			");

			return true;
		}

		public function isExistNewsletterEmail($email) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM newsletter_emails
				WHERE email = '" . sql_quote($email) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function addNewsletterEmail() {
			global $mysql;

			if (!check_email($_POST['newsletter_email'])) {
				return false;
			}

			$aEmail = $this->isExistNewsletterEmail($_POST['newsletter_email']);

			if ($aEmail !== false) {
				if (is_array($aEmail) && $aEmail['newsletter_subscribe'] == 'false') {
					$mysql->query("
						UPDATE newsletter_emails
						SET newsletter_subscribe = 'true'
						WHERE email = '" . sql_quote($_POST['newsletter_email']) . "'
					");

					return true;
				}

				else {
					return 'already';
				}

				return false;
			}

			if (!isset($_POST['newsletter_fname'])) {
				$_POST['newsletter_fname'] = '';
			}

			if (!isset($_POST['newsletter_lname'])) {
				$_POST['newsletter_lname'] = '';
			}

			$mysql->query("
				INSERT INTO newsletter_emails (
					firstname,
					lastname,
					email
				)

				VALUES (
					'" . sql_quote($_POST['newsletter_fname']) . "',
					'" . sql_quote($_POST['newsletter_lname']) . "',
					'" . sql_quote($_POST['newsletter_email']) . "'
				)
			");

			return true;
		}

		public function changeSubscribe($id, $type = 'true') {
			global $mysql;

			$mysql->query("
				UPDATE newsletter_emails
				SET newsletter_subscribe = '" . sql_quote($type) . "'
				WHERE id = '" . intval($id) . "'

			");

			return true;
		}

		public function getTemplate() {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM newsletter_template
				ORDER BY id DESC
				LIMIT 1
			");

			if ($mysql->num_rows() > 0) {
				$template = $mysql->fetch_array();
				$template = $template['template'];
			}

			else {
				$template = '{$CONTENT}';
			}

			return $template;
		}
	}
?>