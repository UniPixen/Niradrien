<?php
	if (isset($_POST['order_id'])) {
		$order_id = (int)$_POST['order_id'];
	}

	else {
		$order_id = 0;
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$cms = new orders();

	$order_info = $cms->get($order_id);

	if ($order_info) {
		// md5sig validation
		$hash  = $_POST['merchant_id'];
		$hash .= $_POST['transaction_id'];
		$hash .= strtoupper(md5(isset($meta['skrill_secret']) ? $meta['skrill_secret'] : 'none'));
		$hash .= $_POST['mb_amount'];
		$hash .= $_POST['mb_currency'];
		$hash .= $_POST['status'];

		$md5hash = strtoupper(md5($hash));
		$md5sig = $_POST['md5sig'];

		if ($md5hash == $md5sig) {
			if ($_POST['status'] == '2') {
				$cms->orderIsPay($order_id);
			}
		}
	}

	exit;
?>