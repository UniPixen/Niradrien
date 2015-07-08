<?php
	_setView (__FILE__);
	_setTitle ($langArray['report_comments']);

	require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
	$commentsClass = new comments();
	
	// Vérifier les commentaites
	if (isset($_GET['check']) && is_numeric($_GET['check'])) {
		$commentsClass->reported($_GET['check']);
		refresh('?m=' . $_GET['m'] . '&c=comments');
	}	
	
	$data = $commentsClass->getAll(START, LIMIT, " report_by <> '0' ");
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=comments&p=', '', PAGE, LIMIT, $commentsClass->foundRows);
	abr ('paging', $p);
	
	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		
		$membersWhere = '';
		foreach($data as $d) {
			$membersWhere[$d['report_by']] = $d['report_by'];
		}
		
		$membersWhere = 'member_id = ' . implode(' OR member_id = ', $membersWhere);
		
		$members = $membersClass->getAll(0, 0, $membersWhere);
		abr('members', $members);
	}
?>