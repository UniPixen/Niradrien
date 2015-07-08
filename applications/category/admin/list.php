<?php
	_setView(__FILE__);
	_setTitle($langArray['list']);

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$cms = new category();

	if (!isset($_GET['p']) || !is_numeric($_GET['p'])) {
		$_GET['p'] = 0;
	}

	if (!isset($_GET['sub_of']) || !is_numeric($_GET['sub_of'])) {
		$_GET['sub_of'] = 0;
	}

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'categories';
		$cms->idColumn = 'id';
		$cms->orderWhere = " AND sub_of = '" . intval($_GET['sub_of']) . "' ";

		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif (isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$data = $cms->getAll(START, LIMIT, " sub_of = '" . intval($_GET['sub_of']) . "' ");
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&sub_of=' . $_GET['sub_of'] . '&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);

	if ($_GET['sub_of'] != 0) {
		$pdata = $cms->get($_GET['sub_of']);
		abr('pdata', $pdata);
	}
?>