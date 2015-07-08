<?php
	_setView (__FILE__);
	_setTitle ($langArray['social_networks']);

	require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
	$social = new social();

	$data = $social->getAll(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=social&p=', '', PAGE, LIMIT, $social->foundRows);
	abr ('paging', $p);
?>