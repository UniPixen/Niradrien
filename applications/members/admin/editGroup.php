<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/addGroup.php');
	_setTitle ($langArray['edit_groups']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}

	require_once ROOT_PATH . '/applications/members/modeles/groups.class.php';
	$cms = new groups();

	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=groups', $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['id']);
	}
?>