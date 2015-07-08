<?php
	_setView (__FILE__);

	if ($_GET['sub_of'] == 0) {
		_setTitle ($langArray['add_page']);
	}

	else {
		_setTitle ($langArray['add_subpage']);
	}

	require_once ROOT_PATH . '/applications/pages/modeles/pages.class.php';
	$cms = new pages();

	if (!isset($_POST['menu'])) {
		$_POST['menu'] = false;
	}

	if (!isset($_GET['sub_of']) || !is_numeric($_GET['sub_of'])) {
		$_GET['sub_of'] = 0;
	}

	if (isset($_POST['add'])) {
		$status = $cms->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list&sub_of=' . $_GET['sub_of'], $langArray['add_complete']);
		}
	}

	else {
		$_POST['visible'] = 'true';
	}

	$_POST['footer'] = isset($_POST['footer']) ? 'true' : 'false';

	if ($_GET['sub_of'] != 0) {
		$pdata = $cms->get($_GET['sub_of']);
		abr('pdata', $pdata);
	}
?>