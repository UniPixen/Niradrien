<?php
	_setView(__FILE__);
	$username = get_id(1);

	$membersClass = new members();
	$member = $membersClass->getByUsername($username);

	if (is_array($member)) {
		if (check_login_bool() && $_SESSION['member']['member_id'] != $member['member_id']) {
			$member['is_follow'] = $membersClass->isFollow($member['member_id']);
		}

		$member['profile_desc'] = replaceEmoticons($member['profile_desc']);

		if (empty($member['profile_desc'])) {
			$member['profile_desc'] = $member['username'] . ' ' . strtolower($langArray['profile_no_descripton']);
		}
		else {
			$member['profile_desc'] = nl2br($member['profile_desc']);
		}

		_setTitle($member['username']);
		abr('member', $member);
		_setDescription($member['profile_desc']);

		// CHARGER LES CATÉGORIES
		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();

		$categories = $categoriesClass->getAll();
		abr('categories', $categories);

		// ENVOYER UN EMAIL
		if (check_login_bool() && isset($_POST['send_email'])) {
			$s = $membersClass->sendEmail();
			
			if ($s === true) {
				refresh('/member/' . $member['username'], $langArray['complete_send_email'], 'complete');
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}

		// SUIVRE UN UTLISATEUR
		if (check_login_bool() && isset($_GET['follow']) && $_SESSION['member']['member_id'] != $member['member_id']) {
			$membersClass->followMember($member['member_id']);
			if (isset($_POST)) {
				if ($member['is_follow']) {
					$text = $langArray['follow'];
					$messageClick = 'Vous ne suivez plus ' . $member['username'];
					$class = 'follow';
				}

				else {
					$text = $langArray['unfollow'];
					$messageClick = 'Vous suivez ' . $member['username'];
					$class = 'unfollow';
				}

				// die('
				// 	jQuery("#follow").html("' . $text . '");
				// ');
			}

			refresh('/member/' . $member['username'], $messageClick, 'complete');
		}

		// CHARGER LES COLLECTIONS PUBLIQUES
		require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
		$collectionsClass = new collections();

		$collections = $collectionsClass->getAll(0, 2, " public = 'true' AND member_id = '" . intval($member['member_id']) . "' ");
		abr('collections', $collections);

		// FICHIERS RÉCOMPENSÉS
		if ($member['featured_product_id'] != '0') {
			require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
			$productClass = new product();

			$featureProduct = $productClass->get($member['featured_product_id'], true);
			abr('featureProduct', $featureProduct);
		}

		// Récupérer les followers
		$follow['to'] = $membersClass->getFollowers($member['member_id'], 0, 999999, 'RAND()', true); // CELUI QUI SUIT
		$follow['to_count'] = $membersClass->foundRows;
		$follow['from'] = $membersClass->getFollowers($member['member_id'], 0, 999999, 'RAND()'); // CELUI QUI EST SUIVIT
		$follow['from_count'] = $membersClass->foundRows;
		abr('follow', $follow);

		// BAGDES
		require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
		$badges = new badges();

		$badges_data = $badges->getAllFront();
		$other_badges = array_map('trim', explode(',', $member['badges']));
		$member_badges = array();

		if ($member['exclusive_author'] == 'true' && isset($badges_data['system']['is_exclusive_author'])) {
			if ($badges_data['system']['is_exclusive_author']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['is_exclusive_author']['photo'])) {
				$member_badges[] = array (
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
					if($v['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $v['photo'])) {
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
			if($badges_data['system']['has_been_featured']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['has_been_featured']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['has_been_featured']['name'],
					'name_en' => $badges_data['system']['has_been_featured']['name_en'],
					'photo' => 'uploads/badges/' . $badges_data['system']['has_been_featured']['photo']
				);
			}
		}

		if (isset($member['statuses']['freefile']) && $member['statuses']['freefile'] && isset($badges_data['system']['has_free_file_month'])) {
			if($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['has_free_file_month']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['has_free_file_month']['name'],
					'name_en' => $badges_data['system']['has_free_file_month']['name_en'],
					'photo' => 'uploads/badges/' . $badges_data['system']['has_free_file_month']['photo']
				);
			}
		}

		if (isset($member['statuses']['featured']) && $member['statuses']['featured'] && isset($badges_data['system']['has_had_product_featured'])) {
			if($badges_data['system']['has_free_file_month']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['has_had_product_featured']['photo'])) {
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
				if($from <= $member['buy'] && $to >= $member['buy']) {
					if($v['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $v['photo'])) {
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
				if($from <= $member['sold'] && $to >= $member['sold']) {
					if($v['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $v['photo'])) {
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
				if($from <= $member['referals'] && $to >= $member['referals']) {
					if($v['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $v['photo'])) {
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
				if(in_array($k, $other_badges) && $b['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $b['photo'])) {
					$member_badges[] = array(
						'name' => $b['name'],
						'name_en' => $b['name_en'],
						'photo' => 'uploads/badges/' . $b['photo']
					);
				}
			}
		}

		if (isset($member['country']['photo']) && $member['country']['photo'] && file_exists(DATA_SERVER_PATH . "/uploads/countries/" . $member['country']['photo'])) {
			$member_badges[] = array(
				'name' => $member['country']['name'],
				'name_en' => $member['country']['name_en'],
				'photo' => '/uploads/countries/' . $member['country']['photo']
			);
		}

		elseif(isset($badges_data['system']['location_global_community']) && $badges_data['system']['location_global_community']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['location_global_community']['photo'])) {
			$member_badges[] = array(
				'name' => $badges_data['system']['location_global_community']['name'],
				'name_en' => $badges_data['system']['location_global_community']['name_en'],
				'photo' => 'uploads/badges/' . $badges_data['system']['location_global_community']['photo']
			);
		}

		if ($member['super_elite_author'] == 'true' && isset($badges_data['system']['super_elite_author'])) {
			if($badges_data['system']['super_elite_author']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['has_been_featured']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['super_elite_author']['name'],
					'name_en' => $badges_data['system']['super_elite_author']['name_en'],
					'photo' => 'uploads/badges/' . $badges_data['system']['super_elite_author']['photo']
				);
			}
		}

		if ($member['elite_author'] == 'true' && isset($badges_data['system']['elite_author'])) {
			if($badges_data['system']['elite_author']['photo'] && file_exists(DATA_SERVER_PATH . "uploads/badges/" . $badges_data['system']['has_been_featured']['photo'])) {
				$member_badges[] = array(
					'name' => $badges_data['system']['elite_author']['name'],
					'name_en' => $badges_data['system']['elite_author']['name_en'],
					'photo' => 'uploads/badges/' . $badges_data['system']['elite_author']['photo']
				);
			}
		}

		abr('member_badges', $member_badges);

		require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
		$social = new social();

		$getSocial = $social->getAll(START, LIMIT, " url != '' AND visible = 'true' ");
		abr('getSocial', $getSocial);

		foreach ($getSocial as $socialKey => $socialValue) {
			$socialUrl[$socialKey] = str_replace('[USERNAME]', $member['social'][$socialKey], $getSocial[$socialKey]['url']);
			abr('socialUrl_' . $socialKey, $socialUrl[$socialKey]);
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>