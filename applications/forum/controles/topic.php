<?php
	_setView(__FILE__);

	$threadID = get_id(3);
	abr('threadID', $threadID);

	$topics = $forumClass->getAllTopics(0, 0, " visible = 'true' ");
	abr('topics', $topics);
	
	$thread = $forumClass->getAllThreads(START, LIMIT, " th.topic_id = " . intval($threadID) . " && th.name != '' && th.reply_to = 0", 'last_message_datetime DESC');
	abr('thread', $thread);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll();
	abr('members', $members);

	if (check_login_bool()) {
		$recentMemberThreads = $forumClass->getAllThreads(START, LIMIT, 'th.reply_to = 0 && th.member_id = ' . $_SESSION['member']['member_id'], 'last_message_datetime DESC');
		abr('recentMemberThreads', $recentMemberThreads);
	}

	if (is_array($thread)) {
		$topic = $forumClass->get($threadID);
		abr('topic', $topic);

		_setTitle($topic['name']);
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>