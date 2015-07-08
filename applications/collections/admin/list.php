<?php
	_setView (__FILE__);
	_setTitle ($langArray['list']);

	require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
	$cms = new collections();

	$data = $cms->getAll(START, LIMIT);
	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $cms->membersWhere);
		abr('members', $members);
	}
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>