<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_topic']);

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
	
	if (isset($_POST['add'])) {
		$status = $forumClass->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['name_en'] = '';
		$_POST['visible'] = 'true';
	}
?>