<?php 
	_setView(__FILE__);
	_setTitle($langArray['choose_payment_method']);
	
	$orderID = 0;
	
	if (isset($_SESSION['tmp']['order_id'])) {
		$orderID = (int)$_SESSION['tmp']['order_id'];
	}

	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$cms = new orders();
	$order_info = $cms->get($orderID);
	
	if ($order_info) {
		$payments = $paymentsClass->getAll();
		$payments_data = array();
		$sort_order = array();

		if ($payments) {
			foreach($payments AS $name => $values) {
				if (isset($values['status']) && $values['status'] == 'active' && $values['purchase'] == 'true') {
					$key = strtolower($name);

					$logo = '';
					if (isset($values['logo'])) {
						$logo = $values['logo'];
					}

					if (isset($values['order_index'])) {
						$sort_order[$key] = $values['order_index'];
					}
					else {
						$sort_order[$key] = 0;
					}

					require_once ROOT_PATH . '/applications/payments/modeles/' . $key . '.class.php';
					$order_obj[$key] = new $key($payments);

					$payments_data[$key] = array(
						'title' => isset($langArray[$key]) ? $langArray[$key] : ucfirst($key),
						'description' => isset($langArray[$key . '_info']) ? $langArray[$key . '_info'] : '',
						'form' => $order_obj[$key]->generateForm($order_info),
						'logo' => $logo
					);
				}
			}

			if ($payments_data) {
				array_multisort($sort_order, SORT_ASC, $payments_data);
				abr('payments_data', $payments_data);
			}

			else {
				addErrorMessage($langArray['no_payment_methods'], '', 'error');
			}
		}

		else {
			addErrorMessage($langArray['no_payment_methods'], '', 'error');
		}
	}

	else {
		addErrorMessage($langArray['order_is_expired'], '', 'error');
	}
?>