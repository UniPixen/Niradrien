<?php
	_setView (__FILE__);
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}

	require_once ROOT_PATH . '/applications/payments/modeles/payments.class.php';
	$cms = new payments(); 
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
		}
	}
	
	else {
		$paymentList = $cms->getAll(START, LIMIT);
		abr('paymentList', $paymentList);
		
		$payment = $cms->getByID($_GET['id']);
		abr('payment', $payment);
	}
?>