<?php
	_setView(__FILE__);

	$productID_name = get_id(1);
	$productID = preg_replace('/[^0-9]/', '', $productID_name);

	$productClass = new product();
	$product = $productClass->get($productID);
	abr('product', $product);

	if (is_array($product)) {
		if ($product['status'] == 'active') {
			_setTitle($product['name']);
			
			if ($currentLanguage['code'] == 'en') {
				_setDescription($product['description_en']);
				$product['description'] = $product['description_en'];
			}
			else {
				_setDescription($product['description']);
			}

			abr('product_description', substr(strip_tags($product['description']), 0, 255));
			_setKeywords(strtolower(implode(',', $product['tags'])));

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			$product['member'] = $membersClass->get($product['member_id']);

			if (check_login_bool() && $product['member_id'] != $_SESSION['member']['member_id']) {
				require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
				$ordersClass = new orders();

				if ($ordersClass->isBuyed($product['id'])) {
					$product['is_buyed'] = langMessageReplace($langArray['already_buyed'], array('URL' => '/account/downloads'));
				}
			}

			$product['description'] = replaceEmoticons($product['description']);
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

			// Achat d'un produit
			if (isset($_SESSION['tmp']['order_id']) && $_SESSION['tmp']['order_id']) {
				$_SESSION['tmp']['order_id'] = 0;
			}

			if (isset($_POST['licence'])) {
				if (!check_login_bool()) {
					$_SESSION['temp']['golink'] = '/product/' . $productID;
					refresh('/login');
				}

				$ordersClass = new orders();
				
				require_once ROOT_PATH . '/applications/payments/modeles/payments.class.php';
				$payments = new payments();
				abr('paypal', $payments->get('PayPal'));

				if ($_POST['licence'] == 'regular') {
					if (isset($_POST['pay_method']) && $_POST['pay_method'] == 'paypal') {
						$orderID = $ordersClass->add($product['paypal_price']);
						
						if (isset($_SESSION['tmp']['deposit_id'])) {
							unset($_SESSION['tmp']['deposit_id']);
						}

						$_SESSION['tmp']['order_id'] = $orderID;
						refresh('/product/payment');
					}

					else {
						if ($_SESSION['member']['total'] < $product['price']) {
							addErrorMessage($langArray['error_not_enought_money'], '', 'error');
						}

						else {
							$ordersClass->buy($product['price']);
							refresh('/account/downloads', $langArray['complete_buy_theme'], 'complete');
						}
					}
				}

				elseif ($_POST['licence'] == 'extended') {
					if (isset($_POST['pay_method']) && $_POST['pay_method'] == 'paypal') {
						$orderID = $ordersClass->add($product['extended_price'], 'true');
						
						if (isset($_SESSION['tmp']['deposit_id'])) {
							unset($_SESSION['tmp']['deposit_id']);
						}

						$_SESSION['tmp']['order_id'] = $orderID;
						refresh('/product/payment');
					}

					else {
						if ($_SESSION['member']['total'] < $product['extended_price']) {
							addErrorMessage($langArray['error_not_enought_money'], '', 'error');
						}

						else {
							$ordersClass->buy($product['extended_price'], true);
							refresh('/account/downloads/', $langArray['complete_buy_theme'], 'complete');
						}
					}
				}
			}

			$coffeeCups = round($product['price'] / 2.5);
			abr('coffeeCups', $coffeeCups);

			// Ajout d'un produit dans la collection
			require_once ROOT_PATH . '/applications/product/controles/collection.php';

			// Ajout d'un produit dans les favoris
			require_once ROOT_PATH . '/applications/product/controles/favorites.php';

			// Produit gratuit
			if ($product['free_file'] == 'true') {
				abr('freeFileMessage', langMessageReplace($langArray['free_file_info'], array('URL' => '/members/download' . $product['id'])));
			}

			// Charger les autres produits de l'auteur
			$otherProducts = $productClass->getAll(0, 9, " status = 'active' AND id <> '" . intval($productID) . "' AND member_id = '" . intval($product['member_id']) . "' ", "RAND()");
			abr('otherProducts', $otherProducts);

			if (!is_array($otherProducts)) {
				abr('otherProductsCount', 0);
			}

			else {
				abr('otherProductsCount', count($otherProducts));
			}

			// Charger les miniatures des produits
			$miniatureProducts = $productClass->getAll(0, 1, " status = 'active' AND id = '" . intval($productID) . "' AND member_id = '" . intval($product['member_id']) . "' ", "RAND()");
			abr('miniatureProducts', $miniatureProducts);

			// Charger les attributs
			require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
			$attributesClass = new attributes();

			$attributes = $attributesClass->getAll(0, 0, $productClass->attributesWhere);
			abr('attributes', $attributes);

			$attributeCategories = $attributesClass->getAllCategories(0, 0, $productClass->attributeCategoriesWhere);
			abr('attributeCategories', $attributeCategories);

			// Charger les catégories
			require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
			$categoriesClass = new category();

			$categories = $categoriesClass->getAll();
			abr('categories', $categories);
			
			require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
			$badges = new badges();

			$badges_data = $badges->getAllFront();
			$member = $product['member'];
			$other_badges = array_map('trim', explode(',', $member['badges']));
			$member_badges = array();

			if ($member['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
				if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['is_exclusive_author']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['is_exclusive_author']['name'],
						'name_en' => $badges_data['system']['is_exclusive_author']['name_en'],
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
						if ($v['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $v['photo'])) {
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
				if ($badges_data['system']['has_been_featured']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['has_been_featured']['name'],
						'name_en' => $badges_data['system']['has_been_featured']['name_en'],
						'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
					);
				}
			}

			if (isset($member['statuses']['freefile']) && $member['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
				if ($badges_data['system']['has_free_file_month']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['has_free_file_month']['name'],
						'name_en' => $badges_data['system']['has_free_file_month']['name_en'],
						'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
					);
				}
			}

			if (isset($member['statuses']['featured']) && $member['statuses']['featured'] && isset($badges_data['system']['has_had_product_featured'])) {
				if ($badges_data['system']['has_free_file_month']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['has_had_product_featured']['name'],
						'name_en' => $badges_data['system']['has_had_product_featured']['name_en'],
						'photo' => 'uploads/badges/' . $badges_data['system']['has_had_product_featured']['photo']
					);
				}
			}

			if ($member['buy'] && isset($badges_data['buyers']) && is_array($badges_data['buyers'])) {
				foreach($badges_data['buyers'] AS $k => $v) {
					list($from, $to) = explode('-', $k);
					if ($from <= $member['buy'] && $to >= $member['buy']) {
						if ($v['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $v['photo'])) {
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

			if ($member['sold'] && isset($badges_data['authors']) && is_array($badges_data['authors'])) {
				foreach($badges_data['authors'] AS $k => $v) {
					list($from, $to) = explode('-', $k);
					if ($from <= $member['sold'] && $to >= $member['sold']) {
						if ($v['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $v['photo'])) {
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

			if ($member['referals'] && isset($badges_data['referrals']) && is_array($badges_data['referrals'])) {
				foreach($badges_data['referrals'] AS $k => $v) {
					list($from, $to) = explode('-', $k);
					if ($from <= $member['referals'] && $to >= $member['referals']) {
						if ($v['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $v['photo'])) {
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

			if (isset($badges_data['other']) && is_array($badges_data['other'])) {
				foreach($badges_data['other'] AS $k => $b) {
					if (in_array($k, $other_badges) && $b['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $b['photo'])) {
						$member_badges[] = array(
							'name' => $b['name'],
							'name_en' => $b['name_en'],
							'photo' => 'uploads/badges/' . $b['photo']
						);
					}
				}
			}

			if (isset($member['country']['photo']) && $member['country']['photo'] && file_exists($config['data_server_path'] . '/uploads/countries/' . $member['country']['photo'])) {
				$member_badges[] = array(
					'name' => $member['country']['name'],
					'name_en' => $member['country']['name_en'],
					'photo' => '/uploads/countries/' . $member['country']['photo']
				);
			}

			elseif (isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['location_global_community']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['location_global_community']['name'],
					'name_en' => $badges_data['system']['location_global_community']['name_en'],
					'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
				);
			}

			if ($member['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
				if ($badges_data['system']['super_elite_author']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['super_elite_author']['name'],
						'name_en' => $badges_data['system']['super_elite_author']['name_en'],
						'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
					);
				}
			}

			if ($member['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
				if ($badges_data['system']['elite_author']['photo'] && file_exists($config['data_server_path'] . 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo'])) {
					$member_badges[] = array(
						'name' => $badges_data['system']['elite_author']['name'],
						'name_en' => $badges_data['system']['elite_author']['name_en'],
						'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
					);
				}
			}

			abr('member_badges', $member_badges);

		    // FAQ
		    require_once ROOT_PATH . '/applications/product/modeles/faq.class.php';
		    $faqClass = new faq();
		    $faqs = $faqClass->CountAll($productID);
		    abr('faqs', $faqs);

			// Slider
			$files = scandir($config['data_server_path'] . 'uploads/products/' . $productID . '/preview/');
			$previewFiles = array();

			if (is_array($files)) {
				foreach($files as $f) {
					if (file_exists($config['data_server_path'] . '/uploads/products/' . $productID . '/preview/' . $f)) {
						$fileInfo = pathinfo($config['data_server_path'] . '/uploads/products/' . $productID . '/preview/' . $f);

						if (isset($fileInfo['extension']) && ( strtolower($fileInfo['extension']) == 'jpg' || strtolower($fileInfo['extension']) == 'png' ) ) {
							$previewFiles[] = $f;
						}
					}
				}
			}

			abr('previewFiles', $previewFiles);
		}

		elseif ($product['status'] == 'deleted' || $product['status'] == 'unapproved') {
			_setTitle($langArray['product_not_longer_available']);
		}
	}

	elseif ((check_login_bool() && $product['status'] == 'unapproved' && $product['member_id'] != $_SESSION['member']['member_id']) || $product['status'] == 'queue') {
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>