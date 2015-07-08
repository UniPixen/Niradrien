<?php
	_setView(__FILE__);
	_setTitle($langArray['add_new_issue']);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/support/modeles/support_categories.class.php';
	$cms = new support_categories();
	
	if (isset($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=category', $langArray['add_complete']);
		}
	}
	
	else {
		$_POST['name'] = '';
		$_POST['name_en'] = '';
		$_POST['visible'] = 'true';
	}
?>