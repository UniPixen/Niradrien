<?php
	_setView(__FILE__);
	_setTitle($langArray['my_sales']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/author';
		refresh('/login');
	}

	if ($_SESSION['member']['author'] != 'true') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	// METTRE ICI LE CODE POUR AFFICHER LES PRODUITS
	$limit = 20;
	$start = (PAGE - 1) * $limit;

	$order = '';

	if (!isset($_GET['sort'])) {
		$_GET['sort'] = '';
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = '';
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$sales = $ordersClass->getSales($_SESSION['member']['member_id']);

	if (is_array($sales)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
		abr('members', $members);

		$products = $productClass->getAll(0, 0, " status = 'active' ");
		abr('products', $products);
	}

	abr('sales', $sales);
	abr('paging', paging('/author/sales' . '?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $ordersClass->foundRows));

	// Charger les catégories
	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>