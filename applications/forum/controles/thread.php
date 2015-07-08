<?php
	_setView(__FILE__);

	$threadID = get_id(3);
	abr('threadID', $threadID);
	
	$thread = $forumClass->getThread(START, LIMIT, $threadID);
	$total_messages = implode($forumClass->getThreadTotalMessages($threadID));

	$existTopics = $forumClass->getAllExistTopics(" visible = 'true' ");
	abr('existTopics', $existTopics);

	if ($thread[$threadID]['deleted'] == 'false' || $thread[$threadID]['deleted'] == 'true' && check_login_bool() && $_SESSION['member']['access']['forum']) {
		if (PAGE > 1) {
			$threadInformations = $forumClass->getThread(0, 1, $threadID);
			$threadInformations['informations'] = $threadInformations[$threadID];
			unset($threadInformations['informations']['comment']);

			$threadID = 'informations';
			abr('threadID', $threadID);

			$thread['informations'] = $threadInformations['informations'];
		}

		else {
			$threadID = get_id(3);
			abr('threadID', $threadID);
		}

		abr('thread', $thread);

		_setTitle($thread[$threadID]['name']);

		if (isset($_GET['p']) && $_GET['p'] == 1) {
			_setDescription(htmlspecialchars($thread[$threadID]['comment']));
		}

		if (is_array($thread[$threadID]) || is_array($thread['informations'])) {
			if (check_login_bool()) {
				$recentMemberThreads = $forumClass->getAllThreads(START, LIMIT, 'th.reply_to = 0 && th.member_id = ' . $_SESSION['member']['member_id'], 'last_message_datetime DESC');
				abr('recentMemberThreads', $recentMemberThreads);

				// MESSAGE SIGNALÉ
				if (check_login_bool() && isset($_POST['report']) && is_numeric($_POST['report']) && isset($_POST['report_reason'])) {
					$s = $forumClass->report(htmlentities($_POST['report']));
					if ($s === true) {
						refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_report_message'], 'complete');
					}
					else {
						addErrorMessage($s, '', 'error');
					}
				}

				// MESSAGE MODÉRÉ
				if (check_login_bool() && isset($_POST['moderate']) && is_numeric($_POST['moderate']) && isset($_POST['post_message'])) {
					if (isset($_POST['censor_message'])) {
						$censor_message = 'true';
					}
					else {
						$censor_message = 'false';
					}

					if (!isset($_POST['post_name'])) {
						$_POST['post_name'] = NULL;
					}
					
					$s = $forumClass->moderate($_POST['moderate'], htmlentities($_POST['post_message']), $censor_message, htmlentities($_POST['post_name']));
					
					if ($s === true) {
						refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_moderate_message'], 'complete');
					}
					else {
						addErrorMessage($s, '', 'error');
					}
				}

				// NOUVEAU MESSAGE
				if (isset($_POST['comment'])) {
					if (isset($_POST['thread_id'])) {
						$s = $forumClass->addThread(htmlentities($_POST['thread_id']));

						if ($s === true) {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_add_forum_reply'], 'complete');
						}

						else {
							addErrorMessage($langArray['error_forum_comment'], '', 'error');
						}
					}

					else {
						addErrorMessage($langArray['error_forum_comment'], '', 'error');
					}
				}

				if (isset($_POST['locked']) && isset($_POST['thread_id'])) {
					$locked = $_POST['locked'];
					$threadID = $_POST['thread_id'];
					$lockThread = $forumClass->lockThread($threadID, $locked);

					if ($lockThread === true) {
						if ($locked == 'true') {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_lock_thread'], 'complete');
						}

						else {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_unlock_thread'], 'complete');
						}
					}

					else {
						addErrorMessage($langArray['error_lock_thread'], '', 'error');
					}
				}

				if (isset($_POST['deleted']) && isset($_POST['thread_id'])) {
					$deleted = $_POST['deleted'];
					$threadID = $_POST['thread_id'];
					$deleteThread = $forumClass->deleteThread($threadID, $deleted);

					if ($deleteThread === true) {
						if ($deleted == 'true') {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_delete_thread'], 'complete');
						}

						else {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_restore_thread'], 'complete');
						}
					}

					else {
						addErrorMessage($langArray['error_delete_thread'], '', 'error');
					}
				}

				if (isset($_POST['sticky']) && isset($_POST['thread_id'])) {
					$sticky = $_POST['sticky'];
					$threadID = $_POST['thread_id'];
					$changeSticky = $forumClass->changeSticky($threadID, $sticky);

					if ($changeSticky === true) {
						if ($sticky == 'true') {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_set_sticky'], 'complete');
						}
						else {
							refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_unset_sticky'], 'complete');
						}
					}

					else {
						addErrorMessage($langArray['error_forum_sticky'], '', 'error');
					}
				}

				if (isset($_POST['topic_id']) && isset($_POST['thread_id'])) {
					$newTopicID = $_POST['topic_id'];
					$threadID = $_POST['thread_id'];
					$moveThread = $forumClass->moveThread($threadID, $newTopicID);

					if ($moveThread === true) {
						refresh('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $threadID, $langArray['complete_move_thread'], 'complete');
					}

					else {
						addErrorMessage($langArray['error_forum_comment'], '', 'error');
					}
				}
			}

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			$members = $membersClass->getAll();

			abr('members', $members);

			// BADGE DU POSTEUR DU MESSAGE
			require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
			$badges = new badges();
			$u_badges = array();
			$badges_data = $badges->getAllFront();
			
			foreach($members as $memberID => $memberData) {
				$other_badges = array_map('trim', explode(',', $memberData['badges']));
				$badges_comments = array();

				if ($memberData['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
					if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['is_exclusive_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo']
						);
					}
				}

				$datetime_inscription = new DateTime($memberData['register_datetime']);
				$datetime_actuelle = new DateTime(date('Y-m-d H:i:s'));

				$interval_dates = $datetime_inscription->diff($datetime_actuelle);
				$interval_dates->format('%y');

				if (isset($badges_data['anciennete']) && is_array($badges_data['anciennete'])) {
					foreach ($badges_data['anciennete'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						
						if ($from <= $interval_dates->format('%y') && $to > $interval_dates->format('%y')) {
							if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
								$badges_comments[] = array(
									'name' => $v['name'],
									'name_en' => $v['name_en'],
									'photo' => 'uploads/badges/' . $v['photo']
								);
							}

							break;
						}
					}
				}

				if ($memberData['featured_author'] == 'true' && isset($badges_data['system']['has_been_featured'])) {
					if ($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_been_featured']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
						);
					}
				}

				if (isset($memberData['statuses']['freefile']) && $memberData['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
					if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_free_file_month']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
						);
					}
				}
				if (isset($memberData['statuses']['featured']) && $memberData['statuses']['featured'] && isset($badges_data['system']['has_had_member_featured'])) {
					if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_had_member_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_had_member_featured']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_had_member_featured']['photo']
						);
					}
				}
				if ($memberData['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
					foreach ($badges_data['buyers'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberData['buy'] && $to >= $memberData['buy']) {
							if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
								$badges_comments[] = array(
									'name' => $v['name'],
									'photo' => 'uploads/badges/' . $v['photo']
								);
							}
							break;
						}
					}
				}

				if ($memberData['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
					foreach ($badges_data['authors'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberData['sold'] && $to >= $memberData['sold']) {
							if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
								$badges_comments[] = array(
									'name' => $v['name'],
									'photo' => 'uploads/badges/' . $v['photo']
								);
							}
							break;
						}
					}
				}

				if ($memberData['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
					foreach ($badges_data['referrals'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberData['referals'] && $to >= $memberData['referals']) {
							if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
								$badges_comments[] = array(
									'name' => $v['name'],
									'photo' => 'uploads/badges/' . $v['photo']
								);
							}
							break;
						}
					}
				}

				if (isset($badges_data['other']) && is_array($badges_data['other'])) {
					foreach ($badges_data['other'] as $k => $b) {
						if (in_array($k, $other_badges) && $b['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $b['photo'])) {
							$badges_comments[] = array(
								'name' => $b['name'],
								'photo' => 'uploads/badges/' . $b['photo']
							);
						}
					}
				}

				if (isset($memberData['country']['photo']) && $memberData['country']['photo'] && file_exists(DATA_SERVER_PATH . '/uploads/countries/' . $memberData['country']['photo'])) {
					$badges_comments[] = array(
						'name' => $memberData['country']['name'],
						'photo' => '/uploads/countries/' . $memberData['country']['photo']
					);
				}

				elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
					$badges_comments[] = array(
						'name' => $badges_data['system']['location_global_community']['name'],
						'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
					);
				}

				if ($memberData['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
					if ($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['super_elite_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
						);
					}
				}

				if ($memberData['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
					if ($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['elite_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
						);
					}
				}

				$u_badges[$memberID] = $badges_comments;
			}

			abr('badges', $u_badges);

			foreach($members as $memberID => $memberData){
				$memberTotalMessages[$memberID] = $forumClass->getMemberTotalMessages($memberID);
			}
			abr('memberTotalMessages', $memberTotalMessages);

			abr('paging', paging('/forum/thread/' . url($thread[$threadID]['name']) . '/' . $thread[$threadID]['id'] . '?p=', '', PAGE, LIMIT, $total_messages));
		}

		else {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	// elseif ($thread[$threadID]['deleted'] == 'true' && check_login_bool() && $_SESSION['member']['access']['forum']) {
	// 	echo 'Salut';
	// }

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>