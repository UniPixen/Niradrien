<?php
	if (function_exists('file_get_contents_curl')) {
		function file_get_contents_curl($url) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

			$data = curl_exec($ch);
			curl_close($ch);

			return $data;
		}
	}

	$productID = get_id(2);

	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();
	$paypal_info = $paymentsClass->get('PayPal');

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$cms = new orders();
	$success_status = array('Completed', 'Pending', 'In-Progress', 'Processed');

	if ($productID == 'pdt' && !isset($_POST['custom'])) {
		if (!isset($_GET['tx']) || $paypal_info['token'] == '') {
			refresh('http://' . $config['domain'] . '/account/downloads');
		}

		if (isset($_GET['cm'])) {
			$order_id = $_GET['cm'];
		}
		else {
			$order_id = 0;
		}

		$order_info = $cms->get($order_id);

		if ($order_info) {
			$request = 'cmd=_notify-synch';
			$request .= '&tx=' . $_GET['tx'];
			$request .= '&at=' . $paypal_info['token'];

			if (isset($paypal_info['sandbox']) && $paypal_info['token']) {
				$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			}
			else {
				$url = 'https://www.paypal.com/cgi-bin/webscr';
			}

			if (ini_get('allow_url_fopen')) {
				$response = file_get_contents($url . '?' . $request);
			}
			else {
				$response = file_get_contents_curl($url . '?' . $request);
			}

			$resp_array = array();
			$verified = false;

			if ($response) {
				$lines = explode("\n", $response);

				if ($lines[0] == 'SUCCESS') {
					$verified = true;
					for ($i = 1; $i < (count($lines) - 1); $i++) {
						list ($key,$val) = explode('=', $lines[$i]);
						$resp_array[urldecode($key)] = urldecode($val);
					}
				}
			}

			if (isset($resp_array['payment_status']) && in_array($resp_array['payment_status'], $success_status) and (float)$_POST['mc_gross'] == (float)$order_info['price']) {
				$cms->orderIsPay($order_id);
				refresh('http://' . $config['domain'] . '/purchase/' . $order_info['code_achat']);
			}
		}
	}

	else {
		if (isset($_POST['custom'])) {
			$order_id = $_POST['custom'];
		}
		else {
			$order_id = 0;
		}

		$order_info = $cms->get($order_id);
		if ($order_info) {
			$request = 'cmd=_notify-validate';

			foreach ($_POST as $key => $value) {
				$request .= '&' . $key . '=' . urlencode($value);
			}

			if (isset($paypal_info['sandbox']) && $paypal_info['sandbox']) {
				$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			else {
				$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
			}

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($request)));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($ch);
			curl_close($ch);

			if (in_array($_POST['payment_status'], $success_status)  and (float)$_POST['mc_gross'] == (float)$order_info['price']) {
				$cms->orderIsPay($order_id);
				refresh('http://' . $config['domain'] . '/purchase/' . $order_info['code_achat']);
			}
		}
	}
?>