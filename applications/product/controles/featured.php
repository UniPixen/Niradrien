<?php
	_setView(__FILE__);
	_setTitle($langArray['featured_files']);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	// Produits récompensés
	$sixMonthsAgo = date('Y-m-d', mktime(0, 0, 0, (date('m') - 6), date('d'), date('Y')));
	$product = $productClass->getAll(0, 0, " status = 'active' AND weekly_to >= '" . date('Y-m-d') . "' AND weekly_to >= '" . $sixMonthsAgo . "' ", "datetime DESC");

	if (is_array($product)) {
		abr('topProduct', array_shift($product));
		$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
		abr('members', $members);
	}
	abr('product', $product);

	// Charger les catégories
	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();
	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	// Auteurs récompensés
	$featuredAuthors = $membersClass->getAll(0, 0, " status = 'activate' AND featured_author = 'true' ");
	abr('featuredAuthors', $featuredAuthors);
?>