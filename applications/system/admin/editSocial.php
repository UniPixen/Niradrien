<?php
	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=social', 'INVALID ID', 'error');
	}

	_setView(__FILE__);
	_setTitle($langArray['edit_social']);
	
	require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
	$cms = new social();
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh('?m=' . $_GET['m'] . '&c=social', $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['id']);
	}		
?>