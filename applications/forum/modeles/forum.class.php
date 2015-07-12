<?php
	class forum extends base {
		public $foundRows = 0;
		public $membersWhere = '';

		public function getAllTopics($start = 0, $limit = 0, $where = '', $order = 'order_index ASC') {
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
				FROM forum
				WHERE (SELECT COUNT(*) FROM forum_threads WHERE topic_id = forum.id) > 0
				ORDER BY $order
				$limitQuery
			");

			// LORSQUE ON A AUCUN MESSAGE, ON EST OBLIGÉ DE PASSER PAR ÇA À LA PLACE …
			// $mysql->query("
			// 	SELECT *
			// 	FROM forum
			// 	WHERE visible = 'true'
			// 	ORDER BY $order
			// 	$limitQuery
			// ");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';
			$this->membersQuery = '';

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				if ($this->membersQuery != '') {
					$this->membersQuery .= ' OR ';
				}
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllExistTopics($start = 0, $limit = 0, $where = '', $order = 'order_index ASC') {
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
				SELECT *,
				(SELECT COUNT(*) FROM forum_threads WHERE topic_id = forum.id) AS nombre_threads
				FROM forum
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
				$return[$d['id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				if ($this->membersQuery != '') {
					$this->membersQuery .= ' OR ';
				}
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllThreads($start = 0, $limit = 0, $where = '', $order = '') {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$whereQuery = '';

			if ($where != '') {
				$whereQuery = " WHERE " . $where;
			}

			$orderQuery = '';

			if ($order != '') {
				$orderQuery = " ORDER BY " . $order;
			}

			$mysql->query("
				SELECT
					th.id,
					th.topic_id,
					th.name AS thread_name,
					th.comment,
					th.datetime,
					th.reply_to,
					th.report_by,
					th.report_reason,
					th.sticky,
					th.locked,
					th.deleted,
					u.member_id,
					COALESCE(nb.dtmax, th.datetime) AS last_message_datetime,
					COALESCE(nb.topic_count, 0) AS topic_count,
					COALESCE(rt.member_id, th.member_id) AS last_message_member_id
				FROM
					forum_threads th
					INNER JOIN members u
					ON th.member_id = u.member_id
					LEFT JOIN (
						-- date dernière réponse + nombre réponses par thread
						SELECT
						reply_to,
						MAX(datetime) AS dtmax,
						COUNT(id) AS topic_count
						FROM forum_threads
						WHERE reply_to <> 0
						GROUP BY reply_to
					) AS nb
					ON th.id = nb.reply_to
					LEFT JOIN forum_threads rt
					ON th.id = rt.reply_to
					AND nb.dtmax = rt.datetime
					LEFT JOIN members lm
					ON rt.member_id = lm.member_id
				$whereQuery
				$orderQuery
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';
			$this->membersQuery = '';

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				if ($this->membersQuery != '') {
					$this->membersQuery .= ' OR ';
				}
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM forum
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function getMemberTotalMessages($id) {
			global $mysql;

			$mysql->query("
				SELECT COUNT(id) as total_member_messages
				FROM forum_threads
				WHERE member_id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function getThread($start = 0, $limit = 0, $id) {
			global $mysql;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT ft.*,
				f.name AS topic_name
				FROM forum_threads ft
				    LEFT JOIN forum f ON ft.topic_id = f.id
				WHERE
				    (ft.id = '" . $id . "' AND ft.name IS NOT NULL)
				    OR
				    ft.reply_to = '" . $id . "'
				ORDER BY ft.datetime ASC
				$limitQuery
			", __FUNCTION__ );

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

		public function getThreadTotalMessages($id) {
			global $mysql;

			$mysql->query("
				SELECT COUNT(*) as total_thread_messages
				FROM forum_threads ft
				    LEFT JOIN forum f ON ft.topic_id = f.id
				WHERE
				    (ft.id = '" . $id . "' AND ft.name IS NOT NULL)
				    OR
				    ft.reply_to = '" . $id . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function edit($id) {
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

			$mysql->query("
				UPDATE forum 
				SET	name = '" . sql_quote(trim($_POST['name'])) . "',
					name_en = '" . sql_quote(trim($_POST['name_en'])) . "',
					visible = '" . sql_quote($_POST['visible']) . "'
				WHERE id = '" . intval($id) . "'
				", __FUNCTION__
			);

			return true;
		}

		public function add() {
			global $mysql, $langArray;

			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (!isset($_POST['name_en']) || trim($_POST['name_en']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			$orderIndex = $this->getNextOrderIndex();

			$mysql->query("
				INSERT INTO forum (
					name,
					name_en,
					visible,
					order_index
				)
				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['name_en']) . "',
					'" . sql_quote($_POST['visible']) . "',
					'" . intval($orderIndex) . "'
				)
			", __FUNCTION__ );

			return true;
		}

		public function addThread($replyTo = 0) {
			global $mysql, $langArray;

			if (isset($_POST['thread_id'])) {
				$_POST['name'] = '';
				$_POST['subject'] = '';
				$_POST['reply_to'] = $_POST['thread_id'];
			}

			if (!isset($_POST['comment']) || trim($_POST['comment']) == '') {
				$error['comment'] = $langArray['comment'];
			}

			if (!isset($_POST['notify'])) {
				$_POST['notify'] = 'false';
			}

			else {
				$_POST['notify'] = 'true';
			}

			$mysql->query("
				INSERT INTO forum_threads (
					member_id,
					name,
					comment,
					datetime,
					notify,
					reply_to,
					report_by
				)

				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['subject']) . "',
					'" . sql_quote($_POST['comment']) . "',
					NOW(),
					'" . sql_quote($_POST['notify']) . "',
					'" . sql_quote($replyTo) . "',
					'0'
				)
			");

			return $mysql->insert_id();
		}

		public function delete($id) {
			global $mysql;

			$row = $this->get($id);

			if (!is_array($row)) {
				return true;
			}

			$mysql->query("
				DELETE FROM forum
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			$mysql->query("
				DELETE FROM forum_threads
				WHERE topic_id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function moderateMessage($id) {
			global $mysql;

			$mysql->query("
				UPDATE forum_threads
				SET moderate = 'true'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function lockThread($threadID, $locked) {
			global $mysql;

			$mysql->query("
				UPDATE forum_threads
				SET locked = '" . sql_quote($locked) . "'
				WHERE id = '" . intval($threadID) . "' && reply_to = '0'
				LIMIT 1
			");

			return true;
		}

		public function deleteThread($threadID, $deleted) {
			global $mysql;

			$mysql->query("
				UPDATE forum_threads
				SET deleted = '" . sql_quote($deleted) . "'
				WHERE id = '" . intval($threadID) . "' && reply_to = '0'
				LIMIT 1
			");

			return true;
		}

		public function changeSticky($threadID, $sticky) {
			global $mysql;

			$mysql->query("
				UPDATE forum_threads
				SET sticky = '" . sql_quote($sticky) . "'
				WHERE id = '" . intval($threadID) . "' && reply_to = '0'
				LIMIT 1
			");

			return true;
		}

		public function moveThread($threadID, $newTopicID) {
			global $mysql;

			$mysql->query("
				UPDATE forum_threads
				SET topic_id = '" . intval($newTopicID) . "'
				WHERE id = '" . intval($threadID) . "' && reply_to = '0'
				LIMIT 1
			");

			return true;
		}

		public function report($id) {
			global $mysql, $langArray;

			$mysql->query("
				UPDATE forum_threads
				SET report_by = '" . intval($_SESSION['member']['member_id']) . "',
					report_reason = '" . sql_quote($_POST['report_reason']) . "'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function moderate($id, $comment, $moderate, $name = NULL) {
			global $mysql, $langArray;

			$mysql->query("
				UPDATE forum_threads
				SET name = '" . $name . "',
					comment = '" . $comment . "',
					moderate = '" . $moderate . "'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function cancelReported($id) {
			global $mysql, $langArray;

			$mysql->query("
				UPDATE forum_threads
				SET report_by = '0',
					report_reason = ''
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}
	}
?>
