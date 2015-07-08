<?php
	_setView(__FILE__);
	_setLayout('blank');

	if (!isset($_GET['author']) || empty($_GET['author'])) {
		$_GET['author'] = 'error';
	}

	if (!isset($_GET['key']) || empty($_GET['key'])) {
		$_GET['key'] = 'error';
	}

	$author = htmlentities($_GET['author']);
	$api_key = htmlentities($_GET['key']);

	require_once ROOT_PATH . '/applications/api/modeles/api.class.php';
	$apiClass = new api();
	
	$balance = json_encode (
		array(
			'balance' => $apiClass->getInformations($author, $api_key)
		)
	);

	abr('balance', $balance);
?>