<?php
	_setView(__FILE__);
	_setTitle($langArray['history']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/account/history';
		refresh('/login');
	}

	if (!isset($_GET['month'])) {
		$_GET['month'] = date('m');
	}

	if (!isset($_GET['year'])) {
		$_GET['year'] = date('Y');
	}

	if (!checkdate($_GET['month'], 1, $_GET['year'])) {
		$_GET['month'] = date('m');
		$_GET['year'] = date('Y');
	}

	abr('download_csv_info', langMessageReplace($langArray['download_csv_info'], array('URL' => '/account/history/?month=' . $_GET['month'] . '&$year=' . $_GET['year'] . '&export')));

	$registrationDate = explode(' ', $_SESSION['member']['register_datetime']);
	$registrationDate = explode('-', $registrationDate[0]);
	abr('registrationDate', $registrationDate);

	$today['month'] = date('m');
	$today['year'] = date('Y') + 1;
	abr('today', $today);

	$nav['prev']['month'] = date('m', mktime(0, 0, 0, ($_GET['month']-1), 1, $_GET['year']));
	$nav['prev']['year'] = date('Y', mktime(0, 0, 0, ($_GET['month']-1), 1, $_GET['year']));
	$nav['next']['month'] = date('m', mktime(0, 0, 0, ($_GET['month']+1), 1, $_GET['year']));
	$nav['next']['year'] = date('Y', mktime(0, 0, 0, ($_GET['month']+1), 1, $_GET['year']));
	
	if ($nav['prev']['month'] < $registrationDate[1] && $nav['prev']['year'] <= $registrationDate[0]) {
		$nav['prev']['show'] = 'false';
	}

	else {
		$nav['prev']['show'] = 'true';
	}

	if ($nav['next']['month'] > date('m') && $nav['next']['year'] >= date('Y')) {
		$nav['next']['show'] = 'false';
	}

	else {
		$nav['next']['show'] = 'true';
	}

	abr('nav', $nav);

	// Obtenir l'historique
	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$history = $ordersClass->getHistory($_SESSION['member']['member_id'], $_GET['month'], $_GET['year']);
	abr('history', $history);

	if (isset($_GET['export'])) {
		header('Content-Type: application/text/x-csv; charset=utf-8; encoding=utf-8');
		header('Content-Disposition: attachment; filename="hadriendesign_history_' . $_GET['year'] . '_' . $_GET['month'] . '.csv"');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		@ob_clean();
		@flush();

		if (is_array($history)) {
			foreach($history as $s) {
				echo '"' . date('d M Y', strtotime($s['datetime'])) . '",';
				
				if ($s['type'] == 'deposit') {
					echo '"' . $langArray['deposit'] . '",';
				}

				elseif ($s['type'] == 'withdraw') {
					echo '"' . $langArray['withdraw_money'] . '",';
				}
				
				elseif ($s['type'] == 'order' && $s['owner_id'] == $_SESSION['member']['member_id']) {
					echo '"' . $langArray['receive_money'] . '",';
				}

				else {
					echo '"' . $langArray['purchase_money'] . '",';
				}

				if ($s['type'] == 'deposit') {
					echo '"' . number_format($s['price'], 2) . ' ' . html_entity_decode($currency['symbol']) . '",';
				}

				elseif ($s['type'] == 'withdraw') {
					echo '"' . number_format($s['price'], 2) . ' ' . html_entity_decode($currency['symbol']) . '",';
				}
				
				elseif ($s['type'] == 'order' && $s['owner_id'] == $_SESSION['member']['member_id']) {
					echo '"' . number_format($s['receive'], 2) . ' ' . html_entity_decode($currency['symbol']) . '",';
				}
				
				else {
					echo '"' . number_format($s['price'], 2) . ' ' . html_entity_decode($currency['symbol']) . '",';
				}

				if ($s['type'] == 'deposit') {
					echo '"' . $langArray['deposit_money'] . '",';
				}

				elseif ($s['type'] == 'withdraw') {
					echo '"' . $langArray['earning_money'] . '",';
				}
				
				elseif ($s['type'] == 'order' && $s['owner_id'] == $_SESSION['member']['member_id']) {
					if ($s['referal'] == 'buy') {
						echo '"' . $langArray['sold_product'] . ' ' . $s['product_name'] . '",';
					}

					else {
						echo '"' . $langArray['referal_money'] . '",';
					}
				}

				else {
					echo '"' . $langArray['buy_product'] . ' ' . $s['product_name'] . '",';
				}

				echo "\n";
			}
		}
		
		die();
	}
?>