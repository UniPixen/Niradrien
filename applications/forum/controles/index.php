<?php
	_setView(__FILE__);
	_setTitle($langArray['forum']);

	$limit = 10;
	$start = (PAGE - 1) * $limit;

	$topics = $forumClass->getAllTopics(0, 0, " name != '' && visible = 'true' ");
	abr('topics', $topics);

	$thread = $forumClass->getAllThreads($start, $limit, 'th.reply_to = 0', 'sticky ASC, last_message_datetime DESC');
	abr('thread', $thread);

	if (check_login_bool()) {
		$recentMemberThreads = $forumClass->getAllThreads(START, LIMIT, 'th.reply_to = 0 && th.member_id = ' . $_SESSION['member']['member_id'], 'last_message_datetime DESC');
		abr('recentMemberThreads', $recentMemberThreads);
	}

	abr('paging', paging('/forum?p=', '', PAGE, $limit, $forumClass->foundRows));

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll();
	abr('members', $members);
?>