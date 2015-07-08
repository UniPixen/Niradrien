<?php
	_setView(__FILE__);
	$command = get_id(2);
	//if($command == 'success') {
		$ordersClass = new orders();
		$s = $ordersClass->success();

		if ($s === true) {
			refresh('/download/', $langArray['complete_buy_theme'], 'complete');
		}

		else {
			refresh('/', $langArray['error_paing'], 'error');
		}
	//}

	else {
		refresh('/', $langArray['error_paing'], 'error');
	}
?>