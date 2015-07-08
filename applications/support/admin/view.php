<?php
	_setView(__FILE__);
	_setTitle($langArray['view_ticket']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}	

	require_once ROOT_PATH . '/applications/support/modeles/support.class.php';
	$cms = new support();
	$data = $cms->get($_GET['id']);

	if (isset($_POST['send'])) {
		$s = $cms->sendAnswer();

		if ($s === true) {
			refresh('?m=' . $_GET['m'] . '&c=list', $langArray['complete_answer_issue'], 'complete');
		}

		else {
			addErrorMessage($langArray['error_answer_issue'], '', 'error');
		}
	}

	$_POST = $data;
?>