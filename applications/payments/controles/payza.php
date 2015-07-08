<?php
	if (isset($_POST['ap_itemcode'])) {
		$order_id = (int)$_POST['ap_itemcode'];
	}

	else {
		$order_id = 0;
	}

	if (isset($_POST['apc_1']) && $_POST['apc_1'] == 'deposit') {
		require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
		$cms = new deposit();

		$order_info = $cms->get($order_id);

		if ($order_info) {
			if (isset($_POST['ap_securitycode']) && $_POST['ap_securitycode'] == ( isset($meta['payza_security_code']) ? $meta['payza_security_code'] : mt_rand() )) {
				$cms->depositIsPay($order_id);	
			}
		}
	}

	else {
		require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
		$cms = new orders();

		$order_info = $cms->get($order_id);
		
		if ($order_info) {
			if (isset($_POST['ap_securitycode']) && $_POST['ap_securitycode'] == ( isset($meta['payza_security_code']) ? $meta['payza_security_code'] : mt_rand() )) {
				$cms->orderIsPay($order_id);
			}
		}
	}

	exit;
?>