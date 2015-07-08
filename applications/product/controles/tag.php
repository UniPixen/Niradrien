<?php
	_setView(__FILE__);

	$tag = get_id(2);
	abr('tag', $tag);

	require_once ROOT_PATH . '/applications/tags/modeles/tags.class.php';
	$tagsClass = new tags();

	_setTitle($tag);

	if (!empty($tag)) {
		$t = $tagsClass->isExistTag($tag);

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

		abr('paging', paging('/tag/' . $tag . '/?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $productClass->foundRows));

		if (is_array($t)) {
			$productClass = new product();

			$product = $productClass->getTagProducts($t['id'], $start, $limit, " AND i.status = 'active' ", $order);

			if (is_array($product)) {
				require_once ROOT_PATH.'/applications/members/modeles/members.class.php';
				$membersClass = new members();
				$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
				abr('members', $members);
			}
		}

		else {
			$product = '';
		}

		abr('product', $product);

		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();
		$categories = $categoriesClass->getAll();
		abr('categories', $categories);

		$tagsList = $tagsClass->getAllTags(0, 10);
		abr('tagsList', $tagsList);

		$discount = array();
		if ($meta['prepaid_price_discount']) {
			if (strpos($meta['prepaid_price_discount'], '%')) {
				$discount = $meta['prepaid_price_discount'];
			}

			else {
				$discount = $meta['prepaid_price_discount'] . ' ' . $currency['symbol'];
			}
		}
		abr('right_discount', $discount);
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>