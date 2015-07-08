<?php
    _setView(__FILE__);
    _setTitle($langArray['groups']);

	require_once ROOT_PATH . '/applications/members/modeles/groups.class.php';
    $cms = new groups();

	$data = $cms->getAll(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=groups&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>