<?php
	_setView(__FILE__);
	_setTitle($langArray['upload_theme']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/upload';
		refresh('/login');
	}

	if ($_SESSION['member']['author'] != 'true') {
		refresh('/become-author');
	}

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>