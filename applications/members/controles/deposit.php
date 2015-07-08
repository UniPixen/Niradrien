<?php
	_setView(__FILE__);
	_setTitle($langArray['deposit_cash_set']);

	$command = get_id(2);
	$depositID = get_id(3);

	if (!check_login_bool() && $command != 'success' && $command != 'notify') {
		$_SESSION['temp']['golink'] = '/' . 'deposit/';
		refresh('/' . 'login');
	}

	if (isset($_SESSION['tmp']['deposit_id']) && $_SESSION['tmp']['deposit_id']) {
		$_SESSION['tmp']['deposit_id'] = 0;
	}

	if ($command == 'success') {
		if (!check_login_bool() || !$depositID) {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}

		require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
		$depositClass = new deposit();
		$info = $depositClass->get($depositID);
		abr('deposit', $info);

		// On laisse accès à la page 1 heure maximum après le paiement
		$paymentDate = $info['datetime'];
		$expirationDate = strtotime($paymentDate) + 3600;
		$dateNow = strtotime(date('Y-m-d H:i:s'));

		if ($expirationDate > $dateNow) {
			if (isset ($_SESSION['member']) && $info['member_id'] == $_SESSION['member']['member_id']) {
				if ($info && $info['paid'] == 'true') {
					_setTitle($langArray['deposit_complete']);
					abr('depositSuccess', true);
				}
				
				else {
					refresh('http://' . $config['domain'] . '/' . 'account/deposit', $langArray['error_deposit'], 'error');
				}
			}
		}

		else {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	if (isset($_POST['amount'])) {
		require_once ROOT_PATH.'/applications/members/modeles/deposit.class.php';
		$depositClass = new deposit();
		$depositID = $depositClass->add();

		if ($depositID !== FALSE) {
			if (isset($_SESSION['tmp']['order_id'])) {
				unset($_SESSION['tmp']['order_id']);
			}

			$_SESSION['tmp']['deposit_id'] = $depositID;
			refresh('/' . 'account/payment');
		}
	}

	$discount = array();
	if ($meta['prepaid_price_discount']) {
		if (strpos($meta['prepaid_price_discount'], '%')) {
			$discount = $meta['prepaid_price_discount'];
		}

		else {
			$discount = $currency['symbol'] . $meta['prepaid_price_discount'];
		}
	}
	abr('right_discount', $discount);
?>