<?php
	_setView (__FILE__);
	_setTitle ($langArray['withdrawal']);

	require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
	$cms = new deposit();

	$data = $cms->getWithdraws(START, LIMIT);
	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $cms->membersWhere);
		abr('members', $members);
	}
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=withdraws&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>