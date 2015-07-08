<?php
	class comments {
		public $foundRows = 0;
		public $membersWhere = '';

		public function getAll($start = 0, $limit = 0, $where = '', $withReply = false, $order = 'datetime DESC') {
			global $mysql;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$whereQuery = '';

			if ($where != '') {
				$whereQuery = " WHERE " . $where;
			}

			$mysql->query("
				SELECT *
				FROM products_comments
				$whereQuery
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';
			$this->membersQuery = '';

			while($d = $mysql->fetch_array()) {
				$d['comment'] = replaceEmoticons($d['comment']);
				$return[$d['id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " reply_to = '" . intval($d['id']) . "' ";

				if ($this->membersQuery != '') {
					$this->membersQuery .= ' OR ';
				}

				$this->membersQuery .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();

			if ($withReply && $whereQuery != '') {
				$mysql->query("
					SELECT *
					FROM products_comments
					WHERE $whereQuery
					ORDER BY datetime ASC
				");

				if ($mysql->num_rows() > 0) {
					while($d = $mysql->fetch_array()) {
						$d['comment'] = replaceEmoticons($d['comment']);
						$return[$d['reply_to']]['reply'][$d['id']] = $d;

						if ($this->membersQuery != '') {
							$this->membersQuery .= ' OR ';
						}

						$this->membersQuery .= " member_id = '" . intval($d['member_id']) . "' ";
					}
				}
			}
			
			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products_comments
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function add($replyTo = 0) {
			global $mysql, $product, $langArray, $config;

			if (!isset($_POST['comment']) || trim($_POST['comment']) == '') {
				return false;
			}

			if (!isset($_POST['reply_notification'])) {
				$_POST['reply_notification'] = 'false';
			}

			else {
				$_POST['reply_notification'] = 'true';
			}

			$mysql->query("
				INSERT INTO products_comments (
					owner_id,
					product_id,
					product_name,
					member_id,
					comment,
					datetime,
					notify,
					reply_to
				)

				VALUES (
					'" . intval($product['member_id']) . "',
					'" . intval($product['id']) . "',
					'" . sql_quote($product['name'])."',
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['comment']) . "',
					NOW(),
					'" . sql_quote($_POST['reply_notification']) . "',
					'" . intval($replyTo) . "'
				)
			");

			if ($replyTo != 0) {
				$comment = $this->get($replyTo);

				if ($comment['notify'] == 'true') {
					require_once ROOT_PATH . '/applications/members/modeles/members.class.php';

					$membersClass = new members();
					$member = $membersClass->get($comment['member_id']);

					require_once SYSTEM_PATH . 'classes/email.class.php';

					$emailClass = new email();
					$emailClass->fromEmail = 'no-reply@' . $config['domain'];
					$emailClass->contentType = 'text/html';
					$emailClass->subject = $langArray['email_new_reply_subject'];

					$emailClass->message = emailTemplate (
						$langArray['email_new_reply_subject_short'],
						langMessageReplace(
							$langArray['email_new_reply_text'], array(
								'PRODUCT_URL' => 'http://' . DOMAIN . '/product/' . $product['id'] . '/' . url($product['name']),
								'PRODUCT_NAME' => $product['name']
							)
						),
						'http://' . DOMAIN . '/product/' . $product['id'] . '/' . url($product['name']) . '/comments#comment-' . $comment['id'],
						$langArray['view'],
						$langArray['email_no_spam']
					);

					$emailClass->to($member['email']);
					$emailClass->send();
					unset($emailClass);
				}
			}

			else {
				$mysql->query("
					UPDATE products
					SET comments = comments + 1
					WHERE id = '" . intval($product['id']) . "'
					LIMIT 1
				");
			}

			return true;
		}

		public function delete($id) {
			global $mysql;

			$row = $this->get($id);

			if (!is_array($row)) {
				return true;
			}

			$mysql->query("
				DELETE FROM products_comments
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			$mysql->query("
				DELETE FROM products_comments
				WHERE reply_to = '" . intval($id) . "'
			");

			if ($row['reply_to'] == '0') {
				$mysql->query("
					UPDATE products
					SET comments = comments - 1
					WHERE id = '" . intval($row['product_id']) . "'
				");
			}

			return true;
		}

		public function report($id) {
			global $mysql, $langArray;

			$mysql->query("
				UPDATE products_comments
				SET report_by = '" . intval($_SESSION['member']['member_id']) . "', report_reason = '" . sql_quote($_POST['report_reason']) . "'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			// ENVOYER UN EMAIL À L'ADMINISTRATEUR
			// $mysql->query("
			// 	SELECT *
			// 	FROM system
			// 	WHERE key = 'admin_mail' OR key = 'report_mail'
			// ");

			// while($d = $mysql->fetch_array()) {
			// 	if ($d['key'] == 'report_mail') {
			// 		$sendTo = $d['value'];
			// 		break;
			// 	}

			// 	$sendTo = $d['value'];
			// }

			// $emailClass = new email();
			// $emailClass->to($sendTo);
			// $emailClass->fromEmail = 'no-reply@' . DOMAIN;
			// $emailClass->contentType = 'text/html';
			// $emailClass->subject = '[' . DOMAIN . '] ' . $langArray['email_report_comment_subject'];
			// $emailClass->message = $_SESSION['member']['username'] . $langArray['email_report_comment_text'];
			// $emailClass->send();

			// unset($emailClass);
			return true;
		}

		public function reported($id) {
			global $mysql, $langArray;

			$mysql->query("
				UPDATE products_comments
				SET report_by = '0', report_reason = ''
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}
	}
?>