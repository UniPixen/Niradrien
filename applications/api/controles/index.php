<?php
	_setView(__FILE__);
	
	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$topMonthlyProducts = $ordersClass->getTopSellers(0, 6);
	if (is_array($topMonthlyProducts)) {
		$members2 = $membersClass->getAll(0, 0, $ordersClass->membersWhere);
		abr('members2', $members2);
	}
	abr('topMonthlyProducts', $topMonthlyProducts);	
	
	include_once (ROOT_PATH . '/applications/error/controles/index.php');
?>