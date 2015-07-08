<?php
	_setView (__FILE__);
	_setTitle ($langArray['newsletter']);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
	$cms = new newsletter();

	$data = $cms->getAll(START, LIMIT, '', true);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>