<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	require_once ROOT_PATH . '/applications/tags/modeles/tags.class.php';
	$cms = new tags();
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
		}
	}
	else {
		$_POST = $cms->get($_GET['id']);
	}
?>