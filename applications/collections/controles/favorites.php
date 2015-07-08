<?php
	_setView(__FILE__);
	_setTitle($langArray['favorites']);

	require_once ROOT_PATH . '/applications/collections/modeles/favorites.class.php';
	$favoritesClass = new favorites();
	$limit = 10;
	$start = (PAGE - 1) * $limit;
	$order = '';

	if (!isset($_GET['sort'])) {
		$_GET['sort'] = '';
	}

	switch($_GET['sort']) {
		default:
			$order = 'fav.datetime';
			$sort = $langArray['date'];
			break;
	}
	abr('sort', $sort);

	if (!isset($_GET['order']) || $_GET['order'] == '' || $_GET['order'] == 'desc') {
		$_GET['order'] = 'desc';
		$order .= ' DESC';
	}

	else {
		$_GET['order'] = 'asc';
		$order .= ' ASC';
	}

	$products = $favoritesClass->getProducts($_SESSION['member']['member_id'], $start, $limit, " AND status = 'active' ", $order);
	if (is_array($products)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $favoritesClass->membersWhere);
		abr('members', $members);
	}
	abr('products', $products);

	// Charger les catégories
	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	abr('paging', paging('/account/collections/?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $favoritesClass->foundRows));
?>