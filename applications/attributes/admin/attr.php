<?php
	_setView (__FILE__);
	_setTitle ($langArray ['attributes']);

	if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
		refresh('/' . adminURL . '/?m=' . $_GET ['m'] . '&c=list');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
	$cms = new attributes();

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'attributes';
		$cms->idColumn = 'id';
		$cms->orderWhere = " AND category_id = '" . intval($_GET['id']) . "' ";
	
		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif(isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}
	
	$data = $cms->getAll(START, LIMIT, " category_id = '" . intval($_GET['id']) . "' ");
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=attr&id=' . $_GET['id'] . '&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$attributesCategoriesClass = new attributes_categories();
	
	$pdata = $attributesCategoriesClass->get($_GET['id']);
	abr('pdata', $pdata);
?>