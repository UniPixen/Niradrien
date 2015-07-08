<?php
	_setView(__FILE__);
	_setTitle($langArray['public_collections']);

	require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
	$collectionsClass = new collections();
	$limit = 20;
	$start = (PAGE - 1) * $limit;
	$order = '';

	if (!isset($_GET['sort'])) {
		$_GET['sort'] = '';
	}

	switch ($_GET['sort']) {
		case 'name':
			$order = 'name';
			break;
		case 'rating':
			$order = 'rating';
			break;
		default:
			$order = 'datetime';
			break;
	}

	if (!isset($_GET['order']) || $_GET['order'] == '' || $_GET['order'] == 'desc') {
		$_GET['order'] = 'desc';
		$order .= ' DESC';
	}

	else {
		$_GET['order'] = 'asc';
		$order .= ' ASC';
	}

	$collections = $collectionsClass->getAll($start, $limit, " public = 'true' ", false, $order);

	if (is_array($collections)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';

		$membersClass = new members();
		$members = $membersClass->getAll(0, 0, $collectionsClass->membersWhere);

		abr('members', $members);
	}

	abr('collections', $collections);
	abr('paging', paging('/' . 'collections/?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $collectionsClass->foundRows));
?>