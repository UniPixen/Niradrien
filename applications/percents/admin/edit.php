<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit_payment_rates']);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		refresh('?m=' . $_GET['m'] . '&c=files&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$cms = new percents();
	
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