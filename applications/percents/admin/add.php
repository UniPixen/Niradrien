<?php
	_setView (__FILE__);
	_setTitle ($langArray['add'] . ' ' . $langArray['country']);

	if (!isset($_POST['percent'])) {
		$_POST['percent'] = '';
	}

	if (!isset($_POST['from'])) {
		$_POST['from'] = '';
	}

	if (!isset($_POST['to'])) {
		$_POST['to'] = '';
	}

	require_once ROOT_PATH . 'applications/percents/modeles/percents.class.php';
	$cms = new percents();

	if (isset($_POST['add'])) {
		$status = $cms->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}
?>