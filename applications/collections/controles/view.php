<?php
	_setView(__FILE__);
	$collectionID = get_id(2);

	require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
	$collectionsClass = new collections();

	$collection = $collectionsClass->get($collectionID);
	if (!is_array($collection) || ($collection['public'] == 'false' && check_login_bool() && $collection['member_id'] != $_SESSION['member']['member_id'])) {
		refresh('/' . 'collections/', $langArray['wrong_collection'], 'error');
	}

	_setTitle($collection['name']);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$collection['member'] = $membersClass->get($collection['member_id']);

	if (check_login_bool()) {
		$collection['rate'] = $collectionsClass->isRate($collectionID);
	}

	abr('collection', $collection);

	// Supprimer un produit
	if (isset($_GET['delete']) && check_login_bool() && $collection['member_id'] == $_SESSION['member']['member_id']) {
		$collectionsClass->deleteBookmark($collectionID, $_GET['delete']);
		refresh('/' . 'collections/view/' . $collectionID, $langArray['complete_delete_bookmark'], 'complete');
	}

	// Mettre à jour la collection
	if (check_login_bool() && isset($_POST['edit']) && $collection['member_id'] == $_SESSION['member']['member_id']) {
		$collectionsClass->edit($collectionID);
		refresh('/' . 'collections/view/' . $collectionID, $langArray['complete_edit_collection'], 'complete');
	}

	// Supprimer la collection
	if (check_login_bool() && isset($_POST['delete']) && $collection['member_id'] == $_SESSION['member']['member_id']) {
		$collectionsClass->delete($collectionID);
		refresh('/' . 'member/collections/', $langArray['complete_delete_collection'], 'complete');
	}

	// Charger les produits liés à la collection
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
		case 'categories':
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

	$product = $collectionsClass->getProducts($collectionID, $start, $limit, " AND status = 'active' ", $order);
	if (is_array($product)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$members = $membersClass->getAll(0, 0, $collectionsClass->membersWhere);
		abr('members', $members);
	}
	abr('product', $product);

	abr('paging', paging('/' . 'collections/view/' . $collectionID . '/?p=', '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'], PAGE, $limit, $collectionsClass->foundRows));

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
	$badges = new badges();

	$badges_data = $badges->getAllFront();
	$member = $collection['member'];
	$other_badges = array_map('trim', explode(',', $member['badges']));
	$member_badges = array();

	if ($member['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
		if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['is_exclusive_author']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo']
			);
		}
	}

	$datetime_inscription = new DateTime($member['register_datetime']);
	$datetime_actuelle = new DateTime(date('Y-m-d H:i:s'));

	$interval_dates = $datetime_inscription->diff($datetime_actuelle);
	$interval_dates->format('%y');

	if (isset($badges_data['anciennete']) && is_array($badges_data['anciennete'])) {
		foreach ($badges_data['anciennete'] AS $k => $v) {
			list($from, $to) = explode('-', $k);
			
			if ($from <= $interval_dates->format('%y') && $to > $interval_dates->format('%y')) {
				if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
					$member_badges[] = array(
						'name' => $v['name'],
						'name_en' => $v['name_en'],
						'photo' => 'uploads/badges/' . $v['photo']
					);
				}
				
				break;
			}
		}
	}

	if ($member['featured_author'] == 'true' && isset($badges_data['system']['has_been_featured'])) {
		if ($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['has_been_featured']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
			);
		}
	}

	if (isset($member['statuses']['freefile']) && $member['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
		if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['has_free_file_month']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
			);
		}
	}

	if (isset($member['statuses']['featured']) && $member['statuses']['featured'] && isset($badges_data['system']['has_had_product_featured'])) {
		if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['has_had_product_featured']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo']
			);
		}
	}

	if ($member['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
		foreach($badges_data['buyers'] AS $k => $v) {
			list($from, $to) = explode('-', $k);
			if ($from <= $member['buy'] && $to >= $member['buy']) {
				if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
					$member_badges[] = array(
						'name' => $v['name'],
						'photo' => 'uploads/badges/' . $v['photo']
					);
				}
				break;
			}
		}
	}

	if ($member['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
		foreach($badges_data['authors'] AS $k => $v) {
			list($from, $to) = explode('-', $k);
			
			if ($from <= $member['sold'] && $to >= $member['sold']) {
				if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
					$member_badges[] = array(
						'name' => $v['name'],
						'photo' => 'uploads/badges/' . $v['photo']
					);
				}

				break;
			}
		}
	}

	if ($member['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
		foreach($badges_data['referrals'] AS $k => $v) {
			list($from, $to) = explode('-', $k);
			
			if ($from <= $member['referals'] && $to >= $member['referals']) {
				if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
					$member_badges[] = array(
						'name' => $v['name'],
						'photo' => 'uploads/badges/' . $v['photo']
					);
				}

				break;
			}
		}
	}

	if (isset($badges_data['other']) && is_array($badges_data['other'])) {
		foreach($badges_data['other'] AS $k => $b) {
			if (in_array($k, $other_badges) && $b['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $b['photo'])) {
				$member_badges[] = array(
					'name' => $b['name'],
					'photo' => 'uploads/badges/' . $b['photo']
				);
			}
		}
	}

	if (isset($member['country']['photo']) && $member['country']['photo'] && file_exists(DATA_SERVER_PATH . '/uploads/countries/' . $member['country']['photo'])) {
		$member_badges[] = array(
			'name' => $member['country']['name'],
			'photo' => '/uploads/countries/' . $member['country']['photo']
		);
	}

	elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
		$member_badges[] = array(
			'name' => $badges_data['system']['location_global_community']['name'],
			'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
		);
	}

	if ($member['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
		if ($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['super_elite_author']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
			);
		}
	}

	if ($member['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
		if ($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['elite_author']['name'],
				'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
			);
		}
	}

	abr('member_badges', $member_badges);
?>