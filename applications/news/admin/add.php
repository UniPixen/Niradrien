<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_a_new']);

	require_once ROOT_PATH . '/applications/news/modeles/news.class.php';
	$cms = new news();

	if (isset ($_POST['add'])) {
		$status = $cms->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['description'] = '';
		$_POST['url'] = '';
		$_POST['photo'] = '';
		$_POST['visible'] = 'true';
	}
?>