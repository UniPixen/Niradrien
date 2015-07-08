<?php
	_setView(__FILE__);
	_setTitle($langArray['thanks_purchase']);

	if (check_login_bool() && isset($_SESSION['tmp']['order_id'])) {
		$order_id = (int)$_SESSION['tmp']['order_id'];

		require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
		$cms = new orders();
		$order_info = $cms->get($order_id);

		if ($order_info['code_achat'] == get_id(2)) {
			// On laisse accès à la page 1 heure maximum après le paiement
			$paymentDate = $order_info['datetime'];
			$expirationDate = strtotime($paymentDate) + 3600;
			$dateNow = strtotime(date('Y-m-d H:i:s'));

			if ($order_info['member_id'] != $_SESSION['member']['member_id'] || $expirationDate < $dateNow) {
				include_once (ROOT_PATH . '/applications/error/controles/index.php');
			}

			abr('order_info', $order_info);
		}

		else {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>