<?php
	_setView(__FILE__);
	_setTitle($langArray['add'] . ' ' . $langArray['answer']);

	if (!isset($_GET['id']) && !is_numeric($_GET['id'])) {
		refresh('/' . adminURL . '/?m=' . $_GET['m'] . '&c=list');
	}

	require_once ROOT_PATH . '/applications/quiz/modeles/answers.class.php';
	$cms = new answers();

	if (isset($_POST['add'])) {
		$_POST['quiz_id'] = $_GET['id'];
		$status = $cms->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=answers&id=' . $_GET['id'], $langArray['add_complete']);
		}
	}

	else {
		$_POST['visible'] = 'true';
	}

	require_once ROOT_PATH . '/applications/quiz/modeles/quiz.class.php';
	$categoriesClass = new quiz();
	$pdata = $categoriesClass->get($_GET['id']);
	abr('pdata', $pdata);
?>