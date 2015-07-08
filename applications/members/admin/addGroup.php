<?php
	_setView(__FILE__);
	_setTitle($langArray['add']);

	require_once ROOT_PATH . '/applications/' . $_GET['m'] . '/modeles/groups.class.php';
	$cms = new groups();
	
	if (isset($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=groups', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['description'] = '';
	}
?>