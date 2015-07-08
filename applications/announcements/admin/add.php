<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_author_announcement']);

	require_once ROOT_PATH . '/applications/announcements/modeles/announcements.class.php';
	$announcements = new announcements();
	
	if (isset($_POST['add'])) {
		$status = $announcements->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['message'] = '';
		$_POST['datetime'] = '';
		$_POST['url'] = '';
		$_POST['photo'] = '';
		$_POST['visible'] = 'true';
	}
?>