<?php
	_setView(__FILE__);
	_setTitle($langArray['new_subject']);

	if (check_login_bool()) {
		$data = $forumClass->getAllTopics(START, LIMIT);
		abr('data', $data);

		if (check_login_bool()) {
			$recentMemberThreads = $forumClass->getAllThreads(START, LIMIT, 'th.reply_to = 0 && th.member_id = ' . $_SESSION['member']['member_id'], 'last_message_datetime DESC');
			abr('recentMemberThreads', $recentMemberThreads);

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			$members = $membersClass->getAll();
			abr('members', $members);
		}

		if (check_login_bool() && isset($_POST['add'])) {
			$topicID = $_POST['topic_id'];
			$topicName = $_POST['subject'];

			if (strlen($topicName) > 8) {
				$newThread = $forumClass->addThread();

				if (isset($newThread)) {
					refresh('/forum/thread/' . url($topicName) . '/' . $newThread, $langArray['complete_add_thread'], 'complete');
				}

				else {
					addErrorMessage($langArray['error_new_thread'], '', 'error');
				}
			}

			else {
				addErrorMessage($langArray['error_thread_name_too_short'], '', 'error');
			}
		}
	}

	else {
		refresh('/login', $langArray['login_to_post_subject'], 'error');
	}
?>
