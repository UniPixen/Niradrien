<?php
	_setView (ROOT_PATH . '/applications/' . $_GET ['m'] . '/admin/addAttr.php');
	_setTitle ($langArray ['edit'] . ' ' . $langArray['attribute']);

	if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
		refresh('/' . adminURL . '/?m=' . $_GET ['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		refresh('?m=' . $_GET['m'] . '&c=files&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
	$cms = new attributes();
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['fid']);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=attr&id=' . $_GET['id'], $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['fid']);
		$_POST['visible'] = 'true';
	}

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$attributesCategoriesClass = new attributes_categories();
	
	$pdata = $attributesCategoriesClass->get($_GET['id']);
	abr('pdata', $pdata);		
?>