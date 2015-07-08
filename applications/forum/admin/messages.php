<?php
	_setView (__FILE__);
	_setTitle ($langArray['report_messages']);

	require_once ROOT_PATH . '/applications/forum/modeles/forum.class.php';
	$forumClass = new forum();
	
	// Vérifier les commentaires
	if (isset($_GET['check']) && is_numeric($_GET['check'])) {
		$forumClass->cancelReported($_GET['check']);
		refresh('?m=' . $_GET['m'] . '&c=messages');
	}

	// Modérer les commentaires
	if (isset($_GET['moderate']) && is_numeric($_GET['moderate'])) {
		$forumClass->moderateMessage($_GET['moderate']);
		refresh('?m=' . $_GET['m'] . '&c=messages');
	}
	
	$reportedMessages = $forumClass->getAllThreads(START, LIMIT, " th.report_by <> '0' && th.moderate <> 'true' ");
	abr('reportedMessages', $reportedMessages);

	$p = paging ('?m=' . $_GET['m'] . '&c=messages&p=', '', PAGE, LIMIT, $forumClass->foundRows);
	abr ('paging', $p);
	
	if (is_array($reportedMessages)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		
		$membersWhere = '';
		foreach($reportedMessages as $d) {
			$membersWhere[$d['report_by']] = $d['report_by'];
		}
		
		$membersWhere = 'member_id = ' . implode(' OR member_id = ', $membersWhere);
		
		$members = $membersClass->getAll(0, 0, $membersWhere);
		abr('members', $members);
	}
?>