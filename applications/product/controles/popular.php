<?php
	_setView(__FILE__);
	_setTitle($langArray['popular_files']);

	$position = '';
	abr('position', $position);

	$year = get_id(2);
	$month = get_id(3);
	$day = get_id(4);
	
	if (!checkdate(intval($month), intval($day), intval($year))) {
		$year = date('Y');
		$month = date('m');
		$day = date('d');
	}

	$dayOfWeek = date('N', mktime(0, 0, 0, $month, $day, $year));
	$dayOfWeek = 7 - $dayOfWeek;
	
	if ($dayOfWeek > 0) {
		$endDate = date('Y-m-d', mktime(0, 0, 0, $month, ($day + $dayOfWeek), $year));
	}

	else {
		if (strlen($month) == 1) {
			$month = '0' . $month;
		}

		if (strlen($day) == 1) {
			$day = '0' . $day;
		}
		$endDate = $year . '-' . $month . '-' . $day;
	}

	$startDate = date('Y-m-d', (strtotime($endDate) - 604800));
	abr('endDate', $endDate);

	if (strtotime($endDate) < strtotime(date('Y-m-d'))) {
		abr('nextDate', date('Y/m/d', (strtotime($endDate) + 604800)));
	}
	abr('prevDate', date('Y/m/d', strtotime($startDate)));

	$month = date('m', strtotime($endDate));

	$endMonthlyDate = date('Y-m-d', mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, date('Y'))), date('Y')));
	$startMonthlyDate = date('Y-m-d', mktime(0, 0, 0, ($month - 3), 1, date('Y')));
	abr('endMonthlyDate', $endMonthlyDate);

	$endMonthlyDate2 = date('Y-m-d', mktime(0, 0, 0, $month, date('t', mktime(0, 0, 0, $month, 1, date('Y'))), date('Y')));
	$startMonthlyDate2 = date('Y-m-d', mktime(0, 0, 0, $month, 1, date('Y')));
	abr('month', $month);

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	require_once ROOT_PATH.'/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
	abr('members', $members);

	$topSellProducts = $ordersClass->getTopSellers(0, 50, " AND paid_datetime > '$startDate 23:59:59' AND paid_datetime < '$endDate 23:59:59' ");
	if (is_array($topSellProducts)) {
		$members = $membersClass->getAll(0, 0, $ordersClass->membersWhere);
		abr('members', $members);
	}
	abr('topSellProducts', $topSellProducts);

	$topMonthlyProducts = $ordersClass->getTopSellers(0, 50, " AND paid_datetime > '$startMonthlyDate 00:00:00' AND paid_datetime < '$endMonthlyDate 23:59:59' ");
	if (is_array($topMonthlyProducts)) {
		$members2 = $membersClass->getAll(0, 0, $ordersClass->membersWhere);
		abr('members2', $members2);
	}
	abr('topMonthlyProducts', $topMonthlyProducts);

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	$topAuthors = $ordersClass->getTopAuthors(0, 20, " AND paid_datetime > '$startMonthlyDate2 00:00:00' AND paid_datetime < '$endMonthlyDate2 23:59:59' ");
	abr('topAuthors', $topAuthors);
?>