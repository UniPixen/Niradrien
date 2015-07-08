<?php
	_setView (__FILE__);
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}	
	
	require_once ROOT_PATH . '/applications/forum/modeles/forum.class.php';
	$forumClass = new forum();
	
	$data = $forumClass->get($_GET['id'], false);
	abr('data', $data);
	
	if (isset($_POST['edit'])) {
		$status = $forumClass->edit($_GET['id'], true);
		
		if ($status !== true) {
			abr('error', $status);
		}
		
		else {
			refresh ('?m=' . $_GET['m'] . '&c=list&p=' . $_GET['p'], $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $data;
	}
?>