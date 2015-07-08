<?php
	_setView(__FILE__);
	_setTitle($langArray['free_files']);

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();

	$data = $cms->getAll(START, LIMIT, " free_file = 'true' ");

	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		
		$members = $membersClass->getAll();
		abr('members', $members);
	}
	abr('data', $data);
?>