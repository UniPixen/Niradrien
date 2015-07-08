<?php
	_setView(__FILE__);
	_setTitle($langArray['check_purchase']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/' . 'author/';
		refresh('/' . 'login');
	}

	if ($_SESSION['member']['author'] != 'true') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll();

	abr('members', $members);

	if (!isset($_POST['key'])) {
		$_POST['key'] = '';
	}

	$key = trim($_POST['key']);
	abr('purchaseKey', $key);

	if ($_POST['key']) {
		if ($ordersClass->isBuyedKey($key)) {
			$purchased = true;
		}
		else {
			$purchased = false;
		}
		abr('purchased', $purchased);

		$product = $ordersClass->getAllBuyed("code_achat = '" . $key . "' AND o.paid = 'true' AND o.type = 'buy'");
		abr('product', $product);
	}

	// Pourcentage de reversement
	$member = $membersClass->get($_SESSION['member']['member_id']);

	require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();
	$percent = $percentsClass->getPercentRow($member);
	abr('commission', $percent);
?>