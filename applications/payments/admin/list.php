<?php
	_setView (__FILE__);
	_setTitle ($langArray['payments']);

	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'payments';
		$cms->idColumn = 'id';
	
		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif(isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$payments = $paymentsClass->getAll();
	abr('payments', $payments);
?>