<?php
	_setView(__FILE__);
	_setTitle($langArray['forum']);

	if (isset($_POST['q'])) {
		$_GET['q'] = $_POST['q'];
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_GET['q'])) {
		$_GET['q'] = '';
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = '';
	}

	if (!isset($_GET['dir'])) {
		$_GET['dir'] = '';
	}

	require_once ROOT_PATH . '/applications/forum/modeles/forum.class.php';
	$forumClass = new forum();

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'forum';
		$cms->idColumn = 'id';

		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif (isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$whereQuery = '';
	$orderQuery = '';

	$data = $forumClass->getAllExistTopics(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '&q=' . $_GET['q'] . '&order=' . $_GET['order'], PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>