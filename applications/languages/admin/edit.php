<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit'] . ' ' . $langArray['language']);

	if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		refresh('?m=' . $_GET['m'] . '&c=files&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	$cms = new languages();
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['fid']);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['fid']);
	}
?>