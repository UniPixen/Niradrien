<?php
	_setView (__FILE__);
	_setTitle ($langArray['send_newsletter']);

	require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
	$cms = new newsletter();

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (isset($_POST['add'])) {
		$status = $cms->add ();
		
		if ($status !== true) {
			abr('error', $status);
		}
		
		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['visible'] = 'true';
	}
?>