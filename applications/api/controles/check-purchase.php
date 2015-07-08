<?php
	_setView(__FILE__);
	_setLayout('blank');

	if (!isset($_GET['author']) || empty($_GET['author'])) {
		$_GET['author'] = 'error';
	}

	if (!isset($_GET['key']) || empty($_GET['key'])) {
		$_GET['key'] = 'error';
	}

	if (!isset($_GET['code']) || empty($_GET['code'])) {
		$_GET['code'] = 'error';
	}

	$author = htmlentities($_GET['author']);
	$api_key = htmlentities($_GET['key']);
	$purchase_code = htmlentities($_GET['code']);

	require_once ROOT_PATH . '/applications/api/modeles/api.class.php';
	$apiClass = new api();
	$checkPurchase = json_encode (
		array(
			'check-purchase' => $apiClass->checkCode($author, $api_key, $purchase_code)
		)
	);

	abr('checkPurchase', $checkPurchase);
?>