<?php
	_setView(__FILE__);
	_setTitle($langArray['top_authors']);

	$limit = 50;
	$start = (PAGE - 1) * $limit;

	abr('number', ($start + 1));

	$members = $membersClass->getAll($start, $limit, " products > 0 AND status = 'activate' ", "sales DESC");
	abr('members', $members);

	abr('paging', paging('/best-authors/?p=', '', PAGE, $limit, $membersClass->foundRows));

	require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
	$badges = new badges();
	$u_badges = array();
	$badges_data = $badges->getAllFront();

	if ($members) {
		foreach ($members as $memberid => $memberdata) {
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
				foreach ($badges_data['anciennete'] AS $k => $v) {
					list($from, $to) = explode('-', $k);
					if ($from <= $interval_dates->format('%y') && $to > $interval_dates->format('%y')) {
						if($v['photo'] && file_exists(DATA_SERVER_PATH . 'uploads/badges/' . $v['photo'])) {
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
	}

	abr('badges', $u_badges);
?>