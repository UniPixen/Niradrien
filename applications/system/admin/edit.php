<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	$cms = new system();
	
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
	
	$_POST['help_text'] = isset($_POST['name']) && isset($langArray[$_POST['name'] . '_help']) ? $langArray[$_POST['name'] . '_help'] : '';
?>