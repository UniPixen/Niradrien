<?php
	_setView(__FILE__);
	_setTitle($langArray['comments']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/' . 'comments/';
		refresh('/login');
	}

	if ($_SESSION['member']['author'] != 'true') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$weekStats = $ordersClass->getWeekStats();
	abr('weekStats', $weekStats);

	require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
	$commentsClass = new comments();
	$comments = $commentsClass->getAll(0, 100, " owner_id = '" . intval($_SESSION['member']['member_id']) . "' AND reply_to = '0' ", true, 'datetime DESC');

	if (is_array($comments)) {
		$membersClass = new members();
		$members = $membersClass->getAll(0, 0, $commentsClass->membersWhere);
		abr('members', $members);
	}
	abr('comments', $comments);

	$commentaires_auteur = count($comments);
	abr('commentaires_auteur', $commentaires_auteur);

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>