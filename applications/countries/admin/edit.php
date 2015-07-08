<?php
	_setView ( ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray ['edit'] . ' ' . $langArray['country']);

	if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		refresh('?m=' . $_GET['m'] . '&c=files&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	require_once ROOT_PATH . '/applications/countries/modeles/countries.class.php';
	$cms = new countries();
	
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