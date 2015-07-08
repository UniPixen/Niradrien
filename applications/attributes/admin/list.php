<?php
	_setView (__FILE__);
	_setTitle ($langArray['list']);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$cms = new attributes_categories();

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'attributes_categories';
		$cms->idColumn = 'id';

		if(isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}
		elseif(isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$data = $cms->getAll(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>