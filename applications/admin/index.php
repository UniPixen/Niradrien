<?php
	if (!isset($_GET['m'])) {
		$_GET['m'] = '';
	}

	// LOAD ORDERS COUNT
	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$total = $ordersClass->getSalesStatus();
	abr('total', $total);

	$ref = $ordersClass->getSalesStatus(" AND datetime > '" . date('Y-m') . "-01 00:00:00' ", 'referal');
	$sales = $ordersClass->getSalesStatus(" AND datetime > '" . date('Y-m') . "-01 00:00:00' ");
	abr('sales', $sales);

	if (is_array($sales)) {
		$sales['referal'] = $ref['receive'];
		$sales['win'] = floatval($sales['total']) - floatval($sales['receive']) - floatval($sales['referal']);
		abr('sales', $sales);
	}
	unset($ref);

	// LOAD MEMBERS COUNT
	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members['month'] = $membersClass->getMembersCount(" register_datetime > '" . date('Y-m') . "-01 00:00:00' AND status = 'activate' ");
	$members['total'] = $membersClass->getMembersCount(" status = 'activate' ");
	abr('members', $members);

	$topAuthors = $membersClass->getAll(0, 5, " status = 'activate' ", "sales DESC");
	abr('topAuthors', $topAuthors);

	// LOAD WITHDRAW
	require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
	$depositClass = new deposit();

	$withdraw['no'] = $depositClass->getWithdrawCount(" paid = 'false' AND datetime > '" . date('Y-m') . "-01 00:00:00' ");
	$withdraw['paid'] = $depositClass->getWithdrawCount(" paid = 'true' AND paid_datetime > '" . date('Y-m') . "-01 00:00:00' ");
	abr('withdraw', $withdraw);

	// CHECK FOR ATTRIBUTES
	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$attributesCategoriesClass = new attributes_categories();

	$attributes = $attributesCategoriesClass->getAll();
	if(!is_array($attributes)) {
		abr('notHaveAttributes', 'true');
	}

	require_once ROOT_PATH . '/applications/reports/modeles/javascript.class.php';

	$referal_sum = $ordersClass->getSalesStatusByDay(" AND datetime > '" . date('Y-m') . "-01 00:00:00' ", 'referal');
	$sales_sum = $ordersClass->getSalesStatusByDay(" AND datetime > '" . date('Y-m') . "-01 00:00:00' ");

	$referal_money = array();
	$sales_money = array();
	$member_money = array();
	$win_money = array();
	$sales_num = array();
	$days = array();

	for ($i = 1; $i <= date('t'); $i++) {
		if (isset($referal_sum[date("Y-m-") . sprintf('%02d', $i)])) {
			$referal_money[] = number_format($referal_sum[date("Y-m-") . sprintf('%02d', $i)]['receive'], 2, '.', '');
		}

		else {
			$referal_money[] = 0;
		}

		if (isset($sales_sum[date("Y-m-") . sprintf('%02d', $i)])) {
			$sales_money[] = number_format($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['total'], 2, '.', '');
			$member_money[] = number_format($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['receive'], 2, '.', '');

			if (isset($referal_sum[date("Y-m-") . sprintf('%02d', $i)]['receive'])) {
				$sales_sum[date("Y-m-") . sprintf('%02d', $i)]['referal'] = $referal_sum[date("Y-m-") . sprintf('%02d', $i)]['receive'];
			}

			if (!isset($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['referal'])) {
				$sales_sum[date("Y-m-") . sprintf('%02d', $i)]['referal'] = 0;
			}

			$sales_num[] = $sales_sum[date("Y-m-") . sprintf('%02d', $i)]['num'];
			$win_money[] = number_format( floatval($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['total']) - floatval($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['receive']) - floatval($sales_sum[date("Y-m-") . sprintf('%02d', $i)]['referal']), 2, '.', '');
		}

		else {
			$sales_money[] = 0;
			$member_money[] = 0;
			$win_money[] = 0;
			$sales_num[] = 0;
		}

		$days[] = $i;
	}

	$new_array = array();
	$new_array[] = array('name' => $langArray['referal_money_this_month_short'], 'data' => $referal_money);
	$new_array[] = array('name' => $langArray['win'], 'data' => $sales_money);
	$new_array[] = array('name' => $langArray['member_win_this_month'], 'data' => $member_money);
	$new_array[] = array('name' => $langArray['grid_win'], 'data' => $win_money);
	$new_array2 = array();
	$new_array2[] = array('name' => $langArray['sales'], 'data' => $sales_num);

	abr('finance_array', javascript::encode($new_array));
	abr('sales_array', javascript::encode($new_array2));
	abr('days', json_encode($days));
	abr('valuta', html_entity_decode($currency['symbol'], ENT_QUOTES, 'utf-8'));
?>