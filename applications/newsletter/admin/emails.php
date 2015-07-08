<?php
	_setView (__FILE__);
	_setTitle ($langArray['newsletter']);

	require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
	$cms = new newsletter();

	if (isset($_GET['subscribe']) && is_numeric($_GET['subscribe'])) {
		$cms->changeSubscribe($_GET['subscribe'], 'true');
	}

	elseif (isset($_GET['unsubscribe']) && is_numeric($_GET['unsubscribe'])) {
		$cms->changeSubscribe($_GET['unsubscribe'], 'false');
	}

	$data = $cms->getAllEmails(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=emails&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>