<?php
	_setView (__FILE__);
	_setTitle ($langArray['add']);

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$cms = new category();
	
	if (!isset($_GET['sub_of']) || !is_numeric($_GET['sub_of'])) {
		$_GET['sub_of'] = 0;
	}
	
	if (isset ($_POST ['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET ['m'] . '&c=list&sub_of=' . $_GET['sub_of'], $langArray['add_complete']);
		}
	}

	else {
		$_POST['visible'] = 'true';
	}
	
	if ($_GET['sub_of'] != 0) {
		$pdata = $cms->get($_GET['sub_of']);
		abr('pdata', $pdata);
	}

	// On initialise les $_POST si jamais il n'existent pas, pour ne pas avoir d'erreur
	if (!isset($_POST['name'])) {
		$_POST['name'] = '';
	}
	if (!isset($_POST['name_en'])) {
		$_POST['name_en'] = '';
	}
	if (!isset($_POST['title'])) {
		$_POST['title'] = '';
	}
	if (!isset($_POST['keywords'])) {
		$_POST['keywords'] = '';
	}
	if (!isset($_POST['description'])) {
		$_POST['description'] = '';
	}
	if (!isset($_POST['description_en'])) {
		$_POST['description_en'] = '';
	}
	if (!isset($_POST['visible'])) {
		$_POST['visible'] = '';
	}
?>