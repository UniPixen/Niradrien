<?php
	_setView(__FILE__);
	_setTitle($langArray['categories_list']);

	$categoryName = get_id(1);
	$subCategoryName = get_id(2);
	$subSubCategoryName = get_id(3);
	$categoryID = 'all'; // Par défaut, tout

	abr('categoryName', $categoryName);
	abr('subCategoryName', $subCategoryName);
	abr('subSubCategoryName', $subSubCategoryName);

	// Si on est sur une page pour afficher les sous-catégories
	if (!empty($subSubCategoryName) && !empty($subCategoryName) && !empty($categoryName)) {
		$category = $categoriesClass->getByKeyword($categoryName);
		$subCategory = $categoriesClass->getByKeyword($subCategoryName);
		$subSubCategory = $categoriesClass->getByKeyword($subSubCategoryName);

		if (isset($subSubCategory['id'])) {
			$categoryID = $subSubCategory['id'];
		}

		else {
			$categoryID = 'error';
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	// Si on est sur une page pour afficher les sous-catégories
	elseif (!empty($subCategoryName) && !empty($categoryName)) {
		$category = $categoriesClass->getByKeyword($categoryName);
		$subCategory = $categoriesClass->getByKeyword($subCategoryName);

		if (isset($subCategory['id'])) {
			$categoryID = $subCategory['id'];
		}

		else {
			$categoryID = 'error';
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	// Dans le cas contaire, on est sur une page de catégories
	else {
		$category = $categoriesClass->getByKeyword($categoryName);
		
		if (!$category['subcategory']) {
			if ($category['id']) {
				$categoryID = $category['id'];
			}
		}

		else {
			$categoryID = 'error';
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	if (is_numeric($categoryID) || $categoryID == 'all') {
		_setTitle($langArray['all_categories']);

		$whereQuery = '';

		if (is_numeric($categoryID)) {
			$category = $categoriesClass->get($categoryID);

			if (!is_array($category) || $category['visible'] == 'false') {
				refresh('/category/', $langArray['wrong_category'], 'error');
			}

			$allCategories = $categoriesClass->getAll(0, 0, " visible = 'true' ");
			$categoryParent = $categoriesClass->getCategoryParents($allCategories, $categoryID);
			$categoryParent = explode(',', $categoryParent);
			$categoryParent = array_reverse($categoryParent);
			array_shift($categoryParent);

			abr('categoryParent', $categoryParent);

			$whereQuery = " AND categories LIKE '%," . intval($categoryID) . ",%' ";
		}

		else {
			$categoryID = 'all';
			$category = 0;
			$categoryParent = array();
		}

		abr('category', $category);

		$allCategories = $categoriesClass->getAllWithChilds(0, " visible = 'true' ");

		// Charger les collections de fichiers
		$limit = 20;
		$start = (PAGE - 1) * $limit;

		$order = '';

		if (!isset($_GET['sort'])) {
			$_GET['sort'] = '';
		}

		switch($_GET['sort']) {
			case 'name':
				$order = 'name';
				$sort = $langArray['title'];
				break;

			case 'category':
				$order = 'categories';
				$sort = $langArray['category'];
				break;

			case 'rating':
				$order = 'rating';
				$sort = $langArray['rating'];
				break;

			case 'sales':
				$order = 'sales';
				$sort = $langArray['sales'];
				break;

			case 'price':
				$order = 'price';
				$sort = $langArray['price'];
				break;

			default:
				$order = 'datetime';
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

		if ($_GET['order'] != 'desc') {
			$_GET['order'] = 'asc';
		}

		if (!isset($_GET['price_min'])) {
			$_GET['price_min'] = '';
		}

		if (!isset($_GET['price_max'])) {
			$_GET['price_max'] = '';
		}

		if ($_GET['price_min'] != '' || $_GET['price_max'] != '') {
			if ($_GET['price_min'] > $_GET['price_max']) {
				// SI LE PRIX MINI EST PLUS GRAND QUE LE PRIX MAX, ON LES INVERSE
				// EX : MIN 20 & MAX 10 = MIN 10 & MAX 20
				list($_GET['price_min'], $_GET['price_max']) = array($_GET['price_max'], $_GET['price_min']);
			}
			$whereQuery .= ' AND price BETWEEN ' . intval($_GET['price_min']) . ' AND ' . intval($_GET['price_max']);
		}

		$product = $productClass->getAll($start, $limit, " status = 'active' " . $whereQuery, $order);

		if ($categoryID != 'all') {
			$categoryPrices = $categoriesClass->getCategoryPricesMinMax($category['id']);
		}
		else {
			$categoryPrices = $categoriesClass->getAllCategoriesPricesMinMax();
		}

		abr('categoryPrices', $categoryPrices);

		if (is_array($product)) {
			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
			abr('members', $members);
		}

		abr('product', $product);
		abr('paging', paging('/' . 'category/' . $category['keywords'] . '/' . $subCategoryName . '?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $productClass->foundRows));

		// Charger les catégories
		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();

		$categories = $categoriesClass->getAll();
		abr('categories', $categories);

		// Fils d'ariane
		if (isset($categoryID) && is_numeric($categoryID)) {
			// SET META INFORMATION
			if ($category['title'] != '') {
				$smarty->assign('title', $category['title']);
			}

			else {
				$smarty->assign('title', $category['name']);
			}

			if ($category['keywords'] != '') {
				$smarty->assign('keywords', $category['keywords']);
			}

			if ($category['description'] != '') {
				$smarty->assign('description', htmlspecialchars_decode($category['description']));
			}

			if ($category['description_en'] != '') {
				$smarty->assign('description_en', htmlspecialchars_decode($category['description_en']));
			}
		}
	}

	else {
		$allCategories = $categoriesClass->getAllWithChilds(0, " visible = 'true' ");
		$listeCategories = $categoriesClass->generateList($allCategories);
		abr('listeCategories', $listeCategories);
	}
?>