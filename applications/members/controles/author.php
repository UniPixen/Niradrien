<?php
	_setView(__FILE__);
	_setTitle($langArray['author']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/author';
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

		foreach($comments as $commentaires_auteur) {
		    $commentaires_auteur = 1;
		    $commentaires_auteur++;
		}
		abr('commentaires_auteur', $commentaires_auteur);
	}
	else {
		abr('commentaires_auteur', 0);
	}
	abr('comments', $comments);

	$authorLastAnnouncement = $announcements->getAuthorAnnouncements(START, 1, " visible = 'true' && type = 'authors'");
	abr('authorLastAnnouncement', $authorLastAnnouncement);

	$authorAnnouncements = $announcements->getAuthorAnnouncements(START, 5, " visible = 'true' && type = 'authors'");
	abr('authorAnnouncements', $authorAnnouncements);

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>