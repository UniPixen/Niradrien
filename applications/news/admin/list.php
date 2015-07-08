<?php
	_setView (__FILE__);
	_setTitle ($langArray['news']);

	require_once ROOT_PATH . '/applications/news/modeles/news.class.php';
	$cms = new news();

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'news';
		$cms->idColumn = 'id';

		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif (isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	require_once $config['system_path'] . '/classes/image.class.php';
	$imageClass = new Image();

	$data = $cms->getAll(START, LIMIT);
	$tmp = array();

	if ($data) {
		foreach ($data AS $key => $d) {
			$tmp[$key] = $d;
			$tmp[$key]['thumb'] = '/static/uploads/news/192x64/' . $d['photo'];
		}
	}

	abr('data', $tmp);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>