<?php 
	_setView(__FILE__);
	_setTitle($langArray['about']);
	
	// PRODUIT LE MOINS CHER
	$lowPrice = $productClass->getAll(0, 1, " status = 'active' ", "price ASC");
	if (is_array($lowPrice)) {
		$lowPrice = array_shift($lowPrice);
		$lowPrice = $lowPrice['price'];
	}
	abr('lowPrice', $lowPrice);

	// PRODUIT LE PLUS CHER
	$highPrice = $productClass->getAll(0, 1, " status = 'active' ", "price DESC");
	if (is_array($highPrice)) {
		$highPrice = array_shift($highPrice);
		$highPrice = $highPrice['price'];
	}
	abr('highPrice', $highPrice);

	// TEAM DU SITE
	require_once ROOT_PATH . '/applications/help/modeles/team.class.php';
	$cms = new team();

	$team = $cms->getAll(START, LIMIT);
	abr('team', $team);
?>