<?php
	_setView(__FILE__);
	_setTitle($langArray['add_attribute']);

	if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
		refresh('/' . adminURL . '/?m=' . $_GET['m'] . '&c=list');
	}

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
	$cms = new attributes();
	
	if (isset($_POST['add'])) {
		$_POST['category_id'] = $_GET['id'];
		$status = $cms->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh('?m=' . $_GET['m'] . '&c=attr&id=' . $_GET['id'], $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['name_en'] = '';
		$_POST['tooltip'] = '';
		$_POST['photo'] = '';
		$_POST['visible'] = 'true';
	}
	
	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$attributesCategoriesClass = new attributes_categories();
	
	$pdata = $attributesCategoriesClass->get($_GET['id']);
	abr('pdata', $pdata);
?>