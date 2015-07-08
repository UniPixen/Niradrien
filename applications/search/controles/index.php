<?php
	_setView(__FILE__);

	if (!isset($_GET['type'])) {
		$_GET['type'] = '';
	}

	if (!isset($_GET['term'])) {
		$_GET['term'] = '';
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = 'asc';
	}

	_setTitle(ucfirst(trim($_GET['term'])));

	$search = '';

	if (isset($_GET['base'])) {
		$s = trim($_GET['base']).' ';
	}

	$search .= trim($_GET['term']);

	abr('searchText', $search);
	// $search = ce que l'on a tappé dans la recherche

	$limit = 20;
	$start = (PAGE - 1) * $limit;

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';

	$membersClass = new members();

	if (!isset($_GET['sort'])) {
		$_GET['sort'] = '';
	}

	switch($_GET['sort']) {
		case 'name':
			$order = 'name';
			$sort = $langArray['title'];
			break;

		case 'rating':
			$order = 'rating';
			$sort = $langArray['rating'];
			break;

		case 'sales':
			$order = 'sales';
			$sort = $langArray['sales'];
			break;

		default:
			$order = 'register_datetime';
			$sort = $langArray['register_datetime'];
			break;
	}
	abr('sort', $sort);

	if (!empty($_GET['term'])) {
		$members = $membersClass->getAll($start, $limit, " status = 'activate' AND (username = '" . sql_quote($search) . "' OR profile_desc LIKE '%" . sql_quote($search) . "%') ", "$order ASC");
		abr('authorSearch', $members);
	}

	// Où chercher
	switch($_GET['type']) {
		// Chercher les collections
		case 'collections':
			abr('type', 'collections');

			if (!empty($search)) {
				require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
				$collectionsClass = new collections();

				if (!isset($_GET['sort'])) {
					$_GET['sort'] = '';
				}

				switch($_GET['sort']) {
					case 'name':
						$order = 'name';
						$sort = $langArray['title'];
						break;

					case 'rating':
						$order = 'rating';
						$sort = $langArray['rating'];
						break;

					default:
						$order = 'datetime';
						$sort = $langArray['time'];
						break;
				}
				abr('sort', $sort);

				$collections = $collectionsClass->getAll($start, $limit, " public = 'true' AND (name = '" . sql_quote($search) . "' OR text LIKE '%" . sql_quote($search) . "%') ", false, "$order ASC");

				if (is_array($collections)) {
					$members = $membersClass->getAll(0, 0, $collectionsClass->membersWhere);
					abr('members', $members);
				}

				abr('results', $collections);

				abr('paging', paging('/search?type=collections&term=' . $search . '&p=', '&sort=' . $_GET['sort'], PAGE, $limit, $collectionsClass->foundRows));
			}

			else {
				header('Location: http://' . DOMAIN . '/collections');
			}
		
		break;

		// Chercher dans le forum
		case 'forum':
			abr('type', 'forum');

			if (!empty($search)) {
				require_once ROOT_PATH . '/applications/forum/modeles/forum.class.php';
				$forumClass = new forum();

				if (!isset($_GET['sort'])) {
					$_GET['sort'] = '';
				}

				switch($_GET['sort']) {
					default:
						$order = 'datetime';
						$sort = $langArray['date'];
						break;
				}
				abr('sort', $sort);

				$threads = $forumClass->getAllThreads($start, $limit, " th.reply_to = 0 && (th.name LIKE '%" . sql_quote($search) . "%' || th.comment LIKE '%" . sql_quote($search) . "%') ", "$order DESC");

				if (is_array($threads)) {
					$members = $membersClass->getAll();
					abr('members', $members);
				}

				abr('results', $threads);

				if ($threads) {
					require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
					$badges = new badges();
					$u_badges = array();
					$badges_data = $badges->getAllFront();

					foreach($members as $memberID => $memberData) {
						$other_badges = array_map('trim', explode(',', $memberData['badges']));
						$badges_comments = array();

						if ($memberData['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
							if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['is_exclusive_author']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo']
								);
							}
						}

						$datetime_inscription = new DateTime($memberData['register_datetime']);
						$datetime_actuelle = new DateTime(date('Y-m-d H:i:s'));

						$interval_dates = $datetime_inscription->diff($datetime_actuelle);
						$interval_dates->format('%y');

						if (isset($badges_data['anciennete']) && is_array($badges_data['anciennete'])) {
							foreach ($badges_data['anciennete'] as $k => $v) {
								list($from, $to) = explode('-', $k);
								
								if ($from <= $interval_dates->format('%y') && $to > $interval_dates->format('%y')) {
									if ($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
										$badges_comments[] = array(
											'name' => $v['name'],
											'name_en' => $v['name_en'],
											'photo' => 'uploads/badges/' . $v['photo']
										);
									}

									break;
								}
							}
						}

						if ($memberData['featured_author'] == 'true' && isset($badges_data['system']['has_been_featured'])) {
							if ($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['has_been_featured']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
								);
							}
						}

						if (isset($memberData['statuses']['freefile']) && $memberData['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
							if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['has_free_file_month']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
								);
							}
						}
						if (isset($memberData['statuses']['featured']) && $memberData['statuses']['featured'] && isset($badges_data['system']['has_had_member_featured'])) {
							if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_had_member_featured']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['has_had_member_featured']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['has_had_member_featured']['photo']
								);
							}
						}
						if ($memberData['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
							foreach ($badges_data['buyers'] as $k => $v) {
								list($from, $to) = explode('-', $k);
								if ($from <= $memberData['buy'] && $to >= $memberData['buy']) {
									if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
										$badges_comments[] = array(
											'name' => $v['name'],
											'photo' => 'uploads/badges/' . $v['photo']
										);
									}
									break;
								}
							}
						}

						if ($memberData['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
							foreach ($badges_data['authors'] as $k => $v) {
								list($from, $to) = explode('-', $k);
								if ($from <= $memberData['sold'] && $to >= $memberData['sold']) {
									if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
										$badges_comments[] = array(
											'name' => $v['name'],
											'photo' => 'uploads/badges/' . $v['photo']
										);
									}
									break;
								}
							}
						}

						if ($memberData['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
							foreach ($badges_data['referrals'] as $k => $v) {
								list($from, $to) = explode('-', $k);
								if ($from <= $memberData['referals'] && $to >= $memberData['referals']) {
									if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
										$badges_comments[] = array(
											'name' => $v['name'],
											'photo' => 'uploads/badges/' . $v['photo']
										);
									}
									break;
								}
							}
						}

						if (isset($badges_data['other']) && is_array($badges_data['other'])) {
							foreach ($badges_data['other'] as $k => $b) {
								if (in_array($k, $other_badges) && $b['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $b['photo'])) {
									$badges_comments[] = array(
										'name' => $b['name'],
										'photo' => 'uploads/badges/' . $b['photo']
									);
								}
							}
						}

						if (isset($memberData['country']['photo']) && $memberData['country']['photo'] && file_exists(DATA_SERVER_PATH . '/uploads/countries/' . $memberData['country']['photo'])) {
							$badges_comments[] = array(
								'name' => $memberData['country']['name'],
								'photo' => '/uploads/countries/' . $memberData['country']['photo']
							);
						}

						elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
							$badges_comments[] = array(
								'name' => $badges_data['system']['location_global_community']['name'],
								'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
							);
						}

						if ($memberData['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
							if ($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['super_elite_author']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
								);
							}
						}

						if ($memberData['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
							if ($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
								$badges_comments[] = array(
									'name' => $badges_data['system']['elite_author']['name'],
									'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
								);
							}
						}

						$u_badges[$memberID] = $badges_comments;
					}

					abr('badges', $u_badges);

					foreach($members as $memberID => $memberData){
						$memberTotalMessages[$memberID] = $forumClass->getMemberTotalMessages($memberID);
					}
					abr('memberTotalMessages', $memberTotalMessages);
					
					abr('paging', paging('/search?type=forum&term=' . $search . '&p=', '&sort=' . $_GET['sort'], PAGE, $limit, $forumClass->foundRows));
				}
			}

			else {
				header('Location: http://' . DOMAIN . '/forum');
			}

			break;

		// Chercher les produits
		default:
			abr('type', 'files');

			if (!empty($search)) {
				require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
				$productClass = new product();

				if (!isset($_GET['sort'])) {
					$_GET['sort'] = '';
				}

				switch($_GET['sort']) {
					case 'name':
						$order = 'name';
						$sort = $langArray['title'];
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

				// Charger les catégories
				$whereQuery = '';
				$pagingUrl = '';

				if (isset($_GET['category'])) {
					if ($whereQuery != '') {
						$whereQuery .= ' OR ';
					}

					$category = $categoriesClass->getByKeyword($_GET['category']);

					$whereQuery = " AND categories LIKE '%" . intval($category['id']) . "%' ";
					$pagingUrl .= '&category=' . $_GET['category'];
				}

				// Charger les collections
				if (isset($_GET['collection_id']) && is_numeric($_GET['collection_id'])) {
					require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
					$collectionsClass = new collections();

					$products = $collectionsClass->getProducts($_GET['collection_id'], $start, $limit, " AND status = 'active' AND (name = '" . sql_quote($search) . "' OR description LIKE '%" . sql_quote($search) . "%') " . $whereQuery, "$order", true);
					if (is_array($products)) {
						$members = $membersClass->getAll(0, 0, $collectionsClass->membersWhere);
						abr('members', $members);
					}
					abr('results', $products);

					abr('paging', paging('/search?type=files&term=' . $search . '&p=', $pagingUrl.'&sort=' . $_GET['sort'], PAGE, $limit, $collectionsClass->foundRows));
				}

				else {
					$products = $productClass->getAll($start, $limit, " status = 'active' AND (name LIKE '%" . sql_quote($search) . "%' OR description LIKE '%" . sql_quote($search) . "%') " . $whereQuery, "$order");
					
					if (is_array($products)) {
						$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
						abr('members', $members);
					}

					abr('results', $products);
					abr('paging', paging('/search?type=files&term=' . $search . '&p=', $pagingUrl.'&sort=' . $_GET['sort'], PAGE, $limit, $productClass->foundRows));
				}

				// Charger les catégories
				require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
				$categoriesClass = new category();
				$categories = $categoriesClass->getAll();
				abr('categories', $categories);
			}

			else {
				header('Location: http://' . DOMAIN . '/category/all');
			}
			
		break;
	}

	$discount = array();

	if ($meta['prepaid_price_discount']) {
		if (strpos($meta['prepaid_price_discount'], '%')) {
			$discount = $meta['prepaid_price_discount'];
		}

		else {
			$discount = $currency['symbol'] . $meta['prepaid_price_discount'];
		}
	}

	abr('right_discount', $discount);
?>