<?php
	_setView(__FILE__); 

	if (!isset($_GET['email'])) {
		refresh('/');
	}

	require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
	$newsletterClass = new newsletter();
	$newsletterClass->deleteEmail($_GET['email']);
	
	addErrorMessage($_GET['email'] . $langArray['complete_unsubscribe'], '', 'complete');
?>