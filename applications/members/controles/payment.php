<?php
	_setView(__FILE__);
	_setTitle($langArray['payment_method']);

	$deposit_id = 0;

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/account/deposit';
		refresh('/login');
	}

	if (isset($_SESSION['tmp']['deposit_id'])) {
		$deposit_id = (int)$_SESSION['tmp']['deposit_id'];
	}

	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();

	require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
	$cms = new deposit();
	$deposit_info = $cms->get($deposit_id);

	if ($deposit_info) {
		$payments = $paymentsClass->getAll();
		$payments_data = array();
		$sort_order = array();

		if ($payments) {
			foreach($payments AS $name => $values) {
				// echo '<p>Valeur du dépôt : ' . $deposit_info['deposit'] . ' et valeur maximum (' . $values['minimum_amount'] . '), valeur maximum (' . $values['maximum_amount'] . ')</p>';

				if ((is_null($values['minimum_amount']) && is_null($values['maximum_amount'])) || ($values['minimum_amount'] == $deposit_info['deposit'])) {
					if (isset($values['status']) && $values['status'] == 'active') {
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
							'form' => $order_obj[$key]->generateDepositForm($deposit_info),
							'logo' => $logo
						);
					}
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
		refresh('/account/deposit', $langArray['deposit_is_expired'], 'error');
	}
?>