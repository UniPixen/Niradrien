<?php
	_setView(__FILE__);
	_setTitle($langArray['answers']);

	if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
		refresh('/' . adminURL . '/?m=' . $_GET['m'] . '&c=list');
	}

	require_once ROOT_PATH . '/applications/quiz/modeles/answers.class.php';
	$cms = new answers();

	$data = $cms->getAll(START, LIMIT, " quiz_id = '" . intval($_GET['id']) . "' ");
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=answers&id=' . $_GET['id'] . '&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr('paging', $p);

	require_once ROOT_PATH . '/applications/quiz/modeles/quiz.class.php';
	$categoriesClass = new quiz();

	$pdata = $categoriesClass->get($_GET['id']);
	abr('pdata', $pdata);
?>