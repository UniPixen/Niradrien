<?php
	_setView(__FILE__);
	_setTitle($meta['website_default_title']);
	_setDescription($meta['website_description']);

	require_once ROOT_PATH . 'applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
	abr('members', $members);

	// PRODUITS RÉCOMPENSÉ DE LA SEMAINE
	$weeklyProducts = $productClass->getAll(0, 4, " status = 'active' AND weekly_to >= '" . date('Y-m-d') . "' ", "datetime DESC");
	abr('weeklyProducts', $weeklyProducts);

	if($productClass->foundRows > 4) {
		abr('haveWeekly', 'yes');
	}

	// CHARGER LES CATÉGORIES
	require_once ROOT_PATH . 'applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	// PRODUITS RÉCENTS
	$whereQuery = '';
	$recentProducts = $productClass->getAll(0, 18, " status = 'active' " . $whereQuery, 'datetime DESC');
	abr('recentProducts', $recentProducts);

	if (is_array($recentProducts)) {
		require_once ROOT_PATH . 'applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
		abr('members', $members);
	}

	// AUTEUR RÉCOMPENSÉ
	require_once ROOT_PATH . 'applications/members/modeles/members.class.php';
	$membersClass = new members();

	$featuredAuthor = $membersClass->getAll(0, 1, " status = 'activate' AND featured_author = 'true' ", 'RAND()');
	if (is_array($featuredAuthor)) {
		$featuredAuthor = array_shift($featuredAuthor);
		$featuredProducts = $productClass->getAll(0, 3, " status = 'active' AND member_id = '" . intval($featuredAuthor['member_id']) . "' ");
		abr('featuredProducts', $featuredProducts);
		abr('featuredAuthorInfo', langMessageReplace($langArray['featured_author_info'], array(
			'USERNAME' => $featuredAuthor['username'],
			'MONTH' => strtolower($langArray['monthArr'][date('n', strtotime($featuredAuthor['register_datetime']))]),
			'YEAR' => date('Y', strtotime($featuredAuthor['register_datetime'])),
			'PRODUCTS' => $featuredAuthor['products'],
			'SALES' => $featuredAuthor['sales']
		)));
	}
	abr('featuredAuthor', $featuredAuthor);


	// PRODUITS SUIVIS
	if (check_login_bool()) {
		$following = $membersClass->getFollowersID($_SESSION['member']['member_id']);
		if (is_array($following)) {
			$whereQuery = '';

			foreach($following as $f) {
				if($whereQuery != '') {
					$whereQuery .= ' OR ';
				}
				$whereQuery .= " member_id = '" . intval($f['follow_id'])."' ";
			}

			$followingProducts = $productClass->getAll(0, 9, " status = 'active' AND ($whereQuery) ", "datetime DESC");
			abr('followingProducts', $followingProducts);

			abr('followingProductsCount', $productClass->foundRows);
			abr('emptyThumb', (9-$productClass->foundRows));
		}
	}

	// MEILLEURS AUTEURS
	$topAuthors = $membersClass->getAll(0, 9, " status = 'activate' and sales > 0 ", "sales DESC");
	abr('topAuthors', $topAuthors);

	abr('topAuthorsCount', $membersClass->foundRows);
	abr('emptyThumb', (9 - $membersClass->foundRows));

	// CATÉGORIES AU HASARD
	$randCategories = array_rand($mainCategories);
	abr('randCategories', $randCategories);

	// PRODUIT LE MOINS CHER
	$lowPrice = $productClass->getAll(0, 1, " status = 'active' ", "price ASC");
	if (is_array($lowPrice)) {
		$lowPrice = array_shift($lowPrice);
		$lowPrice = $lowPrice['price'];
	}
	abr('lowPrice', $lowPrice);

	// NEWS
	require_once ROOT_PATH . 'applications/news/modeles/news.class.php';
	$news = new news();
	$data = array();
	foreach($news->getAll(0, 1, "visible = 'true'") AS $key => $value) {
		if ($value['photo']) {
			$data[$key] = $value;
			$data[$key]['thumb'] = 'static/uploads/news/260x140/' . $value['photo'];
		}
	}

	abr('news_data', $data);
?>