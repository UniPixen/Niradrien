<?php
	_setView(__FILE__);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'WRONG ID', 'error');
	}

	require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
	$cms = new comments();

	if (isset($_GET['report']) && is_numeric($_GET['report'])) {
		$cms->reported($_GET['report']);
	}

	$data = $cms->getAll(START, LIMIT, " product_id = '" . $_GET['id'] . "' ");

	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		$members = $membersClass->getAll(0, 0, $cms->membersWhere);
		abr('members', $members);
	}
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=comments&id=' . $_GET['id'] . '&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$productClass = new product();

	$product = $productClass->get($_GET['id']);
	_setTitle($product['name'] . ' &rsaquo; ' . $langArray['comments']);
?>