<?php
	_setView (__FILE__);
	_setTitle ($langArray['add']);

	$cms = new members();
	
	if (isset ($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}
	
	require_once ROOT_PATH . '/applications/' . $_GET['m'] . '/modeles/groups.class.php';
	$g = new groups();
	
	$groups = $g->getAll();
	abr('groups', $groups);
?>