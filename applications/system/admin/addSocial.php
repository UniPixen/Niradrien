<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_social']);
	
	require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
	$social = new social();

	if (isset($_POST['add'] )) {
		$status = $social->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh('?m=' . $_GET['m'] . '&c=social', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['icon'] = '';
		$_POST['color'] = '';
		$_POST['site_username'] = '';
		$_POST['url'] = '';
		$_POST['visible'] = 'true';
	}
?>