<?php
	_setView(__FILE__);
	_setTitle($langArray['collections']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/' . 'account/collections';
		refresh ('/' . 'login');
	}

	require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
	$collectionsClass = new collections();

	if (isset($_POST['add'])) {
		$collectionsClass->add();
		refresh('/' . 'account/collections', $langArray['complete_add_collection'], 'complete');
	}

	$collections = $collectionsClass->getAll(0, 0, " member_id = '" . intval($_SESSION['member']['member_id']) . "' ", true);
	abr('collections', $collections);

	$membersClass = new members();
	$members = $membersClass->getAll(0, 0, $collectionsClass->membersWhere);
	abr('members', $members);
?>