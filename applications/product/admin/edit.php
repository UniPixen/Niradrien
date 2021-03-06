<?php
	_setView (__FILE__);
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$data = $cms->get($_GET['id'], false);
	$data['member'] = $membersClass->get($data['member_id']);
	abr('data', $data);

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
	$attributesClass = new attributes();

	if (isset($data['categories'][0]) && is_array($data['categories'][0])) {
		$first_category = array_shift($data['categories'][0]);
	}
	else {
		$first_category = 0;
	}

	$attributes = $attributesClass->getAllWithCategories(" visible = 'true' AND categories LIKE '%," . (int)$first_category . ",%' ");
	abr('attributes', $attributes);

	if (isset($_POST['edit'])) {
		$status = $cms->edit ($_GET['id'], true);
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list&p=' . $_GET['p'], $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $data;
	}

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	if(!isset($_POST['categories'])) {
		$_POST['categories'] = array();
	}

	$tmp = array();
	
	if (isset($_POST['category'])) {
		$tmp = (array)$_POST['category'];
	}

	else {
		foreach ($_POST['categories'] AS $row => $categories1) {
			$cid = end($categories1);
			$tmp[$cid] = $cid;
		}
	}


	$allCategories = $categoriesClass->getAllWithChilds(0, " visible = 'true' ");
	$categoriesSelect = $categoriesClass->generateSelect($allCategories, $tmp, (int)$first_category);
	abr('categoriesSelect', $categoriesSelect);
?>