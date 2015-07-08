<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/quiz/modeles/quiz.class.php';
	$cms = new quiz();

	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list&p=' . $_GET['p'], $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['id']);
	}
?>