<?php
	_setView(__FILE__);

	$productID = get_id(2);
	$productClass = new product();
	$product = $productClass->get($productID);

	if (is_array($product)) {
		if ($product['status'] == 'deleted') {
			header('Location: http://' . DOMAIN . '/product/' . $productID);
		}

		if (check_login_bool() && $product['member_id'] != $_SESSION['member']['member_id']) {
			require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
			$ordersClass = new orders();

			if ($ordersClass->isBuyed($product['id'])) {
				$product['is_buyed'] = langMessageReplace($langArray['already_buyed'], array('URL' => '/account/downloads'));
			}
		}

		_setTitle($langArray['comments_for'] . ' ' . $product['name']);
		abr('product_description', substr(strip_tags($product['description']), 0, 255));

		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$product['member'] = $membersClass->get($product['member_id']);
		abr('product', $product);

		if ($product['votes'] > 2) {
			// Obtenir l'ensemble des notes
			$productRatings['ratings'] = $productClass->getProductRates($product['id']);
			// Obtenir le nombre total de notes
			$productRatings['count'] = count($productClass->getProductRates($product['id']));
			// Obtenir la moyenne de toutes les notes
			$productRatings['average'] = 0;
			foreach ($productRatings['ratings'] as $ratingArray) {
				$productRatings['average'] += $ratingArray['rate'];
			}
			$productRatings['average'] = round($productRatings['average'] / $productRatings['count'], 2);
			// Obtenir les statistiques pour chaques notes
			$productRatings['stats'] = array_fill(1, 5, 0);
			foreach ($productRatings['ratings'] as $ratingArray) {
				$productRatings['stats'][$ratingArray['rate']]++;
			}
			// On déclare la variable
			abr('productRatings', $productRatings);
		}

		require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
		$commentsClass = new comments();

		// COMMENTAIRE SIGNALÉ
		if (check_login_bool() && isset($_POST['report']) && is_numeric($_POST['report']) && isset($_POST['report_reason'])) {
			$s = $commentsClass->report($_POST['report']);
			if ($s === true) {
				refresh('/product/comments/' . $productID . '/' . url($product['name']), $langArray['complete_report_comment'], 'complete');
			}
			else {
				addErrorMessage($s, '', 'error');
			}
		}

		// AJOUTER UN COMMENTAIRE
		if (check_login_bool() && isset($_POST['add'])) {
			$s = $commentsClass->add();
			if($s === true) {
				refresh('/product/comments/' . $productID . '/' . url($product['name']), $langArray['complete_add_comment'], 'complete');
			}
			else {
				addErrorMessage($langArray['error_product_comment'], '', 'error');
			}
		}

		elseif (isset($_POST['add_reply'])) {
			if (!isset($_POST['comment_id'])) {
				$_POST['comment_id'] = 0;
			}

			$s = $commentsClass->add($_POST['comment_id']);

			if ($s === true) {
				refresh('/product/comments/' . $productID . '/' . url($product['name']), $langArray['complete_add_reply'], 'complete');
			}

			else {
				addErrorMessage($langArray['error_product_comment'], '', 'error');
			}
		}


		$comments = $commentsClass->getAll(START, LIMIT, " product_id = '" . intval($productID) . "' AND reply_to = '0' ", true, 'datetime ASC');

		if (is_array($comments)) {
			$members = $membersClass->getAll(0, 0, $commentsClass->membersQuery);
			abr('members', $members);

			require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
			$ordersClass = new orders();

			$buyFromMembers = $ordersClass->isProductBuyed($productID, $commentsClass->membersQuery);
			abr('buyFromMembers', $buyFromMembers);

			// BADGE DU POSTEUR DU COMMENTAIRE
			require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
			$badges = new badges();
			$u_badges = array();
			$badges_data = $badges->getAllFront();
			
			foreach($members as $memberid => $memberdata){
				$other_badges = array_map('trim', explode(',', $memberdata['badges']));
				$badges_comments = array();

				if ($memberdata['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
					if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['is_exclusive_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo']
						);
					}
				}

				$datetime_inscription = new DateTime($memberdata['register_datetime']);
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

				if ($memberdata['featured_author'] == 'true' && isset($badges_data['system']['has_been_featured'])) {
					if ($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_been_featured']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
						);
					}
				}

				if (isset($memberdata['statuses']['freefile']) && $memberdata['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
					if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_free_file_month']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
						);
					}
				}
				if (isset($memberdata['statuses']['featured']) && $memberdata['statuses']['featured'] && isset($badges_data['system']['has_had_product_featured'])) {
					if ($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['has_had_product_featured']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo']
						);
					}
				}
				if ($memberdata['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
					foreach ($badges_data['buyers'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberdata['buy'] && $to >= $memberdata['buy']) {
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

				if ($memberdata['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
					foreach ($badges_data['authors'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberdata['sold'] && $to >= $memberdata['sold']) {
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

				if ($memberdata['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
					foreach ($badges_data['referrals'] as $k => $v) {
						list($from, $to) = explode('-', $k);
						if ($from <= $memberdata['referals'] && $to >= $memberdata['referals']) {
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

				if (isset($memberdata['country']['photo']) && $memberdata['country']['photo'] && file_exists(DATA_SERVER_PATH . '/uploads/countries/' . $memberdata['country']['photo'])) {
					$badges_comments[] = array(
						'name' => $memberdata['country']['name'],
						'photo' => '/uploads/countries/' . $memberdata['country']['photo']
					);
				}

				elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
					$badges_comments[] = array(
						'name' => $badges_data['system']['location_global_community']['name'],
						'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
					);
				}

				if ($memberdata['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
					if ($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['super_elite_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
						);
					}
				}

				if ($memberdata['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
					if ($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
						$badges_comments[] = array(
							'name' => $badges_data['system']['elite_author']['name'],
							'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
						);
					}
				}
				$u_badges[$memberid] = $badges_comments;
			}

			abr('badges', $u_badges);



		}
		abr('comments', $comments);

		abr('paging', paging('/product/comments/' . $productID . '/' . url($product['name']) . '/?p=', '', PAGE, LIMIT, $commentsClass->foundRows));

		// Ajout d'un produit dans la collection
		require_once ROOT_PATH . '/applications/product/controles/collection.php';

		$coffeeCups = round($product['price'] / 2.5);
		abr('coffeeCups', $coffeeCups);

		// Ajout d'un produit dans les favoris
		require_once ROOT_PATH . '/applications/product/controles/favorites.php';

		// SI LE PRODUIT EST GRATUIT
		if ($product['free_file'] == 'true') {
			abr('freeFileMessage', langMessageReplace($langArray['free_file_info'], array('URL' => '/members/download/' . $product['id'])));
		}

		// CHARGER LES AUTRES PRODUITS
		$otherProducts = $productClass->getAll(0, 7, " status = 'active' AND id <> '" . intval($productID) . "' ", "RAND()");
		abr('otherProducts', $otherProducts);
		abr('otherProductsCount', count($otherProducts));

		// CHARGER LES ATTRIBUTS
		require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
		$attributesClass = new attributes();

		$attributes = $attributesClass->getAll(0, 0, $productClass->attributesWhere);
		abr('attributes', $attributes);

		$attributeCategories = $attributesClass->getAllCategories(0, 0, $productClass->attributeCategoriesWhere);
		abr('attributeCategories', $attributeCategories);

		// CHARGER LES CATÉGORIES
		require_once ROOT_PATH.'/applications/category/modeles/category.class.php';
		$categoriesClass = new category();

		$categories = $categoriesClass->getAll();
		abr('categories', $categories);

		# FAQ
		require_once ROOT_PATH . '/applications/product/modeles/faq.class.php';

		$faqClass = new faq();
		$faqs = $faqClass->CountAll($productID);
		abr('faqs', $faqs);

		$discount = array();
		if ($meta['prepaid_price_discount']) {
			if(strpos($meta['prepaid_price_discount'], '%')) {
				$discount = $meta['prepaid_price_discount'];
			}
			else {
				$discount = $currency['symbol'] . $meta['prepaid_price_discount'];
			}
		}
		abr('right_discount', $discount);

		// BADGE DE L'ASIDE, LE POSTEUR DU THÈME
		require_once ROOT_PATH.'/applications/system/modeles/badges.class.php';
		$badges = new badges();

		$badges_data = $badges->getAllFront();

		$member = $product['member'];

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
			foreach ($badges_data['anciennete'] as $k => $v) {
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
			foreach ($badges_data['buyers'] as $k => $v) {
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
			foreach ($badges_data['authors'] as $k => $v) {
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
			foreach($badges_data['referrals'] as $k => $v) {
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
			foreach ($badges_data['other'] as $k => $b) {
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
			if($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['elite_author']['name'],
					'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
				);
			}
		}

		abr('member_badges', $member_badges);
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>