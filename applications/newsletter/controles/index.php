<?php 
	_setView(__FILE__);
	
	$newsletterClass = new newsletter();
	$newsletters = $newsletterClass->getAll();

	abr('newsletters', $newsletters);
?>