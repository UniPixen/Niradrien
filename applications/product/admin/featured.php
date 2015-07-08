<?php
	_setView(__FILE__);
	_setTitle($langArray['placed_in_front_products']);

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();

	$data = $cms->getAll(START, LIMIT, " weekly_to ");

	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		
		$members = $membersClass->getAll();
		abr('members', $members);
	}
	abr('data', $data);
?>