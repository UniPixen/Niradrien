<?php
	_setView(__FILE__);
	_setTitle($langArray['earnings']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/author/earnings';
		refresh('/login');
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$currentMonth = date('n');
	abr('currentMonth', $currentMonth);

	$membersClass = new members();

	$member = $membersClass->get($_SESSION['member']['member_id']);
	abr('member', $member);

	if ($member['author'] != 'true') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	$memberRegisterDate = explode(' ', $member['register_datetime']);
	$memberRegisterDate = explode('-', $memberRegisterDate[0]);
	$memberRegisterDate = $memberRegisterDate[0];

	// SI ON A L'ANNÉE ET LE MOIS : ON AFFICHE SES JOURS
	if (get_id(2) && get_id(3)) {
		$year = get_id(2);
		$month = get_id(3);

		$sales = $ordersClass->getAll(START, LIMIT, " paid_datetime > '" . date('Y-m-d 23:59:59', mktime(0, 0, 0, ($month - 1), date('t', mktime(0, 0, 0, ($month - 1), 1, $year)), $year)) . "' && paid_datetime < '" . date('Y-m-d 00:00:00', mktime(0, 0, 0, ($month + 1), 1, $year)) . "' && paid = 'true' AND type = 'buy' AND owner_id = '" . intval($_SESSION['member']['member_id']) . "' ", "paid_datetime ASC");

		abr('year', $year);
		
		$show_earning_month = true;
		$show_earning_year = false;

		// On affiche la liste des mois de l'année pour le graphique
		$countMonthDays = date ('j', mktime(0, 0, 0, $month + 1, 1, $year) - 1);
		$chartLabel = array();
		// On affiche les jours de l'années en fonction du mois
		for ($daysList = 1; $daysList <= $countMonthDays; $daysList++) { 
			array_push($chartLabel, '\'' . $daysList . '\'');
		}
		$chartLabel = implode(',', $chartLabel);
		abr('chartLabel', $chartLabel);

		$dataMonths = array_pad (array(), $countMonthDays, 0); // On définit le tableau, soit le nombre de mois dans l'année

		if (is_array($sales)) {
			$ordersData = array();

			foreach($sales as $s) {
				$day = explode(' ', $s['paid_datetime']);
				$day = explode('-', $day[0]);
				
				$monthSales = explode(' ', $s['paid_datetime']);
				$monthSales = explode('-', $monthSales[0]);

				$yearSales = explode(' ', $s['paid_datetime']);
				$yearSales = explode('-', $yearSales[0]);

				if(!isset($ordersData[$day[2]])) {
					$ordersData[$day[2]]['sale'] = 1;
					$ordersData[$day[2]]['earning'] = $s['receive'];
				}
				else {
					$ordersData[$day[2]]['sale']++;
					$ordersData[$day[2]]['earning'] += $s['receive'];
				}
			}

			$dataNumbers = array();
			
			foreach($ordersData as $day => $r) {
				$dataNumbers[$day - 1] = number_format($r['earning'], 2);
				$date[] = $day . ' ' . strtolower($langArray['monthArr'][$monthSales[1]]);
			}

			abr('date', $date);

			$dataMonths = array_replace($dataMonths, $dataNumbers);

			if (array_key_exists($currentMonth, $r)) {
				foreach ($value as $key2 => $value2) {
					if ($key2 == $currentMonth) {
						$monthEarning = $value2['total'];
						$monthSales = $value2['buy'];
					}
				}
			}

			else {
				$monthSales = 0;
				$monthEarning = 0;
			}
		}

		else {
			$monthEarning = 0;
			$monthSales = 0;
			$ordersData = array();
		}

		abr('ordersData', $ordersData);
		
		abr('monthSales', $monthSales);
		abr('monthEarning', $monthEarning);

		$jsonData = json_encode($dataMonths); // On convertit notre tableau en jSON
		abr('jsonData', $jsonData);

		abr (
			'earningsBreadcrumb',
			'<ul>
				<li><a href="/members/earnings' . '" class="categorie-list">' . $langArray['years'] . '</a></li>
				<li><a href="/members/earnings/' . $year . '/" class="categorie-list">' . $langArray['months_of'] . ' ' . $year .'</a></li>
				<li><span class="categorie-list">' . $langArray['monthArr'][$month] . '</span></li>
			</ul>'
		);
	}

	// SI ON A L'ANNÉE MAIS PAS LE MOIS : ON AFFICHE LES MOIS DE L'ANNÉE
	elseif (!get_id(2) && get_id(3)) {
		$year = get_id(2);
		$month = NULL;
		$sales = $ordersClass->getAll(START, LIMIT, " paid_datetime > '" . date('Y-m-d 23:59:59', mktime(0, 0, 0, ($month), date('t', mktime(0, 0, 0, ($month), 1, $year)), $year)) . "' AND paid_datetime < '" . date('Y-m-d 00:00:00', mktime(0, 0, 0, ($month + 1), 1, ($year + 1))) . "' AND paid = 'true' AND type = 'buy' AND owner_id = '" . intval($_SESSION['member']['member_id']) . "' ", "paid_datetime ASC");
		
		abr('year', $year);
		
		$show_earning_month = false;
		$show_earning_year = true;

		// On affiche la liste des mois de l'année pour le graphique
		$chartLabel = array();
		for ($monthsList = 1; $monthsList <= 12; $monthsList++) { 
			array_push($chartLabel, '\'' . $langArray['monthArr'][$monthsList] . '\'');
		}
		$chartLabel = implode(',', $chartLabel);
		abr('chartLabel', $chartLabel);

		$dataMonths = array_pad (array(), 12, 0); // On définit le tableau, donc 12 mois

		if (is_array($sales)) {
			$ordersData = array();

			foreach($sales as $s) {
				$month = explode(' ', $s['paid_datetime']);
				$month = explode('-', $month[0]);
				
				$monthSales = explode(' ', $s['paid_datetime']);
				$monthSales = explode('-', $monthSales[0]);

				$yearSales = explode(' ', $s['paid_datetime']);
				$yearSales = explode('-', $yearSales[0]);

				if(!isset($ordersData[$month[1]])) {
					$ordersData[$month[1]]['sale'] = 1;
					$ordersData[$month[1]]['earning'] = $s['receive'];
				}
				else {
					$ordersData[$month[1]]['sale']++;
					$ordersData[$month[1]]['earning'] += $s['receive'];
				}
			}

			$dataNumbers = array();

			foreach($ordersData as $month => $r) {
				$dataNumbers[$month - 1] = number_format($r['earning'], 2);
				$date[] = $langArray['monthArr'][$month];
			}

			abr('date', $date);

			$dataMonths = array_replace($dataMonths, $dataNumbers);

			if (array_key_exists($currentMonth, $r)) {
				foreach ($value as $key2 => $value2) {
					if ($key2 == $currentMonth) {
						$monthEarning = $value2['total'];
						$monthSales = $value2['buy'];
					}
				}
			}

			else {
				$monthSales = 0;
				$monthEarning = 0;
			}
		}

		else {
			$monthEarning = 0;
			$monthSales = 0;
			$ordersData = array();
		}

		abr('ordersData', $ordersData);
		
		abr('monthSales', $monthSales);
		abr('monthEarning', $monthEarning);

		$jsonData = json_encode($dataMonths); // On convertit notre tableau en jSON
		abr('jsonData', $jsonData);

		abr (
			'earningsBreadcrumb',
			'<ul>
				<li><a href="/members/earnings' . '" class="categorie-list">' . $langArray['years'] . '</a></li>
				<li><span class="categorie-list">' . $langArray['months_of'] . ' ' . $year . '</span></li>
			</ul>'
		);
	}

	// SI ON A RIEN : ON AFFICHE LES ANNÉES
	else {
		$year = date('Y');
		$month = date('n');
		$firstYear = $memberRegisterDate;
		$sales = $ordersClass->getAll(START, LIMIT, " paid_datetime > '" . date($firstYear . '-01-01 00:00:00', mktime(0, 0, 0, ($month - 1), date('t', mktime(0, 0, 0, ($month - 1), 1, $year)), $year)) . "' AND paid_datetime < '" . date('Y-m-d 00:00:00', mktime(0, 0, 0, ($month + 1), 1, $year)) . "' AND paid = 'true' AND type = 'buy' AND owner_id = '" . intval($_SESSION['member']['member_id']) . "' ", "paid_datetime ASC");
		abr('year', $year);
		
		$show_earning_month = false;
		$show_earning_year = true;

		// On affiche la liste des années pour le graphique
		$chartLabel = array();
		for ($yearsList = $year; $yearsList >= $firstYear; $yearsList--) { 
			array_push($chartLabel, '\'' . $yearsList . '\'');
		}
		$chartLabel = array_reverse($chartLabel);
		$chartLabel = implode(',', $chartLabel);
		abr('chartLabel', $chartLabel);

		$dataYears = array_pad (array(), $year - $firstYear, 0); // On définit le tableau, donc toutes les années depuis 2010

		if (is_array($sales)) {
			$ordersData = array();

			foreach($sales as $s) {
				$yearSales = explode(' ', $s['paid_datetime']);
				$yearSales = explode('-', $yearSales[0]);

				$day = explode(' ', $s['paid_datetime']);
				$day = explode('-', $day[0]);

				$monthSales = explode(' ', $s['paid_datetime']);
				$monthSales = explode('-', $monthSales[0]);

				if(!isset($ordersData[$yearSales[0]])) {
					$ordersData[$day[0]]['sale'] = 1;
					$ordersData[$day[0]]['earning'] = $s['receive'];
				}
				else {
					$ordersData[$day[0]]['sale']++;
					$ordersData[$day[0]]['earning'] += $s['receive'];
				}
			}

			$dataNumbers = array();
			
			foreach($ordersData as $day => $r) {
				$dataNumbers[$day - $firstYear] = number_format($r['earning'], 2);
				$date[] = $day;
			}

			abr('date', $date);

			$dataYears = array_replace($dataYears, $dataNumbers);

			if (array_key_exists($currentMonth, $r)) {
				foreach ($value as $key2 => $value2) {
					if ($key2 == $currentMonth) {
						$monthEarning = $value2['total'];
						$monthSales = $value2['buy'];
					}
				}
			}

			else {
				$monthSales = 0;
				$monthEarning = 0;
			}
		}

		else {
			$monthEarning = 0;
			$monthSales = 0;
			$ordersData = array();
		}

		abr('ordersData', $ordersData);
		
		abr('monthSales', $monthSales);
		abr('monthEarning', $monthEarning);

		$jsonData = json_encode($dataYears); // On convertit notre tableau en jSON
		abr('jsonData', $jsonData);

		abr (
			'earningsBreadcrumb',
			'<ul>
				<li><span class="categorie-list">' . $langArray['years'] . '</span></li>
			</ul>'
		);

		// SI ON EST À LA BASE DE EARNINGS, ON REDIRIGE VERS LE MOIS ACTUEL
		header('Location: http://' . DOMAIN . '/author/earnings/' . $year . '/' . $month . '');
	}

	abr('show_earning_month', $show_earning_month);
	abr('show_earning_year', $show_earning_year);

	// POURCENTAGE DE REVERSEMENT
	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	
	if ($percent['to'] == '0') {
		$percent['more'] = '-';
	}

	else {
		$percent['more'] = floatval($percent['to']) - floatval($member['sold']);
	}

	abr('percent', $percent);

	$earnings = array(
		'sales' => 0,
		'sales_earning' => 0,
		'referal' => 0,
		'total' => 0
	);

	$maxSales = 0;

	$earningArr = false;

	// OBTENIR LES DONNÉES DES VENTES
	if (is_array($sales)) {
		$ordersData = array();
		
		foreach($sales as $r) {
			$date = explode(' ', $r['paid_datetime']);
			$date = explode('-', $date[0]);

			if (isset($ordersData[$date[0]][$date[1]]['buy'])) {
				$ordersData[$date[0]][$date[1]]['buy']++;
			}

			else {
				$ordersData[$date[0]][$date[1]]['buy'] = 1;
			}

			if (isset($ordersData[$date[0]][$date[1]]['total'])) {
				$ordersData[$date[0]][$date[1]]['total'] += $r['receive'];
			}

			else {
				$ordersData[$date[0]][$date[1]]['total'] = $r['receive'];
			}

			if (isset($earningArr[$date[0]][$date[1]])) {
				$earningArr[$date[0]][$date[1]] += $r['receive'];
			}

			else {
				$earningArr[$date[0]][$date[1]] = $r['receive'];
			}

			if($ordersData[$date[0]][$date[1]]['buy'] > $maxSales) {
				$maxSales = $ordersData[$date[0]][$date[1]]['buy'];
			}

			$earnings['sales']++;
			$earnings['sales_earning'] += $r['receive'];
			$earnings['total'] += $r['receive'];
		}

		unset($sales);
		$sales = $ordersData;
		unset($ordersData);
	}
	abr('sales', $sales);

	if ($maxSales > 0) {
		$saleIndex = 300 / floatval($maxSales);
	}

	else {
		$saleIndex = 0;
	}

	abr('saleIndex', $saleIndex);

	// OBTENIR LES REVENUS DE PARRAINAGE
	$referals = $ordersClass->getAll(START, LIMIT, " paid = 'true' AND type = 'referal' AND owner_id = '" . intval($member['member_id']) . "' ", "paid_datetime ASC");
	if (is_array($referals)) {
		$ordersData = array();
		
		foreach($referals as $r) {
			$date = explode(' ', $r['paid_datetime']);
			$date = explode('-', $date[0]);

			if ($r['product_id'] == '0') {
				if(isset($ordersData[$date[0]][$date[1]]['deposit'])) {
					$ordersData[$date[0]][$date[1]]['deposit']++;
				}

				else {
					$ordersData[$date[0]][$date[1]]['deposit'] = 1;
				}
			}

			else {
				if (isset($ordersData[$date[0]][$date[1]]['buy'])) {
					$ordersData[$date[0]][$date[1]]['buy']++;
				}

				else {
					$ordersData[$date[0]][$date[1]]['buy'] = 1;
				}
			}

			if (isset($ordersData[$date[0]][$date[1]]['total'])) {
				$ordersData[$date[0]][$date[1]]['total'] += $r['receive'];
			}

			else {
				$ordersData[$date[0]][$date[1]]['total'] = $r['receive'];
			}

			if (isset($earningArr[$date[0]][$date[1]])) {
				$earningArr[$date[0]][$date[1]] += $r['receive'];
			}

			else {
				$earningArr[$date[0]][$date[1]] = $r['receive'];
			}

			$earnings['referal'] += $r['receive'];
			$earnings['total'] += $r['receive'];
		}

		unset($referals);
		$referals = $ordersData;
		unset($ordersData);
	}

	abr('referals', $referals);
	abr('earnings', $earnings);
	abr('earningArr', $earningArr);

	if (is_array($earningArr)) {
		$maxSales = 0;
		
		foreach($earningArr as $e) {
			foreach ($e as $r) {
				if ($r > $maxSales) {
					$maxSales = $r;
				}
			}
		}

		$earningIndex = ($maxSales > 0) ? 300 / floatval($maxSales) : 0;
		abr('earningIndex', $earningIndex);
	}

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>
