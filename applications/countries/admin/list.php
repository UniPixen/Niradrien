<?php
	_setView (__FILE__);
	_setTitle ($langArray['countries']);

	require_once ROOT_PATH . '/applications/countries/modeles/countries.class.php';
	$cms = new countries();

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'countries';
		$cms->idColumn = 'id';

		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif (isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$data = $cms->getAll(START, LIMIT);
	abr('data', $data);

	$p = paging('?m=' . $_GET ['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>