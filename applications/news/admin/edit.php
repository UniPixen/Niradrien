<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit'] . ' ' . $langArray['news']);

	if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		refresh('?m=' . $_GET['m'] . '&c=list&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/news/modeles/news.class.php';
	$cms = new news();

	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['fid']);

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['fid']);
		$_POST['thumb'] = '/static/uploads/news/192x64/' . $_POST['photo'];
	}
?>