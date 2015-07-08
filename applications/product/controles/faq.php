<?php
	_setView(__FILE__);

	$productID = get_id(2);
	$productClass = new product();
	$product = $productClass->get($productID);

	if (is_array($product)) {
		if ($product['status'] == 'deleted') {
			header('Location: http://' . DOMAIN . '/product/' . $productID . '/' . url($product['name']));
		}

		_setTitle($langArray['faqs_for'] . ' ' . $product['name']);
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

		// ADD FAQ ENTRY
		if (check_login_bool() && $product['member_id'] == $_SESSION['member']['member_id'] && isset($_POST['add'])) {
			$faqClass = new faq();
			$s = $faqClass->add($productID);
			
			if ($s === true) {
				refresh('/product/faq/' . $productID . '/' . url($product['name']), $langArray['complete_add_faq'], 'complete');
			}

			else {
				$message = '<ul>';
				foreach($s as $e) {
					$message .= '<li>' . $e . '</li>';
				}
				$message .= '</ul>';
				addErrorMessage($message, '', 'error');
			}
		}

		require_once ROOT_PATH . '/applications/product/modeles/faq.class.php';
		$faqClass = new faq();

		if (check_login_bool() && $product['member_id'] == $_SESSION['member']['member_id'] && isset($_GET['del']) && is_numeric($_GET['del'])) {
			$faqClass->delete($_GET['del'], $productID);
			refresh('/product/faq/' . $productID . '/', $langArray['complete_delete_faq'], 'complete');
		}

		// LOAD FAQ
		$faq = $faqClass->getAll($productID);
		abr('faq', $faq);

		$coffeeCups = round($product['price'] / 2.5);
		abr('coffeeCups', $coffeeCups);

		// Ajout d'un produit dans la collection
		require_once ROOT_PATH . '/applications/product/controles/collection.php';

		// Ajout d'un produit dans les favoris
		require_once ROOT_PATH . '/applications/product/controles/favorites.php';

		// IS FREE FILE
		if ($product['free_file'] == 'true') {
			abr('freeFileMessage', langMessageReplace($langArray['free_file_info'], array('URL' => '/account/downloads/' . $product['id'])));
		}

		// LOAD OTHER PRODUCTS
		$otherProducts = $productClass->getAll(0, 6, " status = 'active' AND id <> '" . intval($productID) . "' AND member_id = '" . intval($product['member_id']) . "' ", "RAND()");
		abr('otherProducts', $otherProducts);
		if (!is_array($otherProducts)) {
			abr('otherProductsCount', 0);
		}
		else {
			abr('otherProductsCount', count($otherProducts));
		}

		// LOAD ATTRIBUTES
		require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
		$attributesClass = new attributes();

		$attributes = $attributesClass->getAll(0, 0, $productClass->attributesWhere);
		abr('attributes', $attributes);

		$attributeCategories = $attributesClass->getAllCategories(0, 0, $productClass->attributeCategoriesWhere);
		abr('attributeCategories', $attributeCategories);

		// LOAD CATEGORIES
		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();

		$categories = $categoriesClass->getAll();
		abr('categories', $categories);

		// FAQ
	    $faqs = $faqClass->CountAll($productID);
	    abr('faqs', $faqs);

	    $member = $product['member'];

	    require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
		$badges = new badges();

		$badges_data = $badges->getAllFront();

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
		} elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
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
	}
	
	if (!is_array($product) || (check_login_bool() && $product['status'] == 'unapproved' && $product['member_id'] != $_SESSION['member']['member_id']) || $product['status'] == 'queue' || $product['status'] == 'extended_buy') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>