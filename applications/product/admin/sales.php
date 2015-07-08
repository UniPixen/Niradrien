<?php
	_setView(__FILE__);
	_setTitle($langArray['sales']);

	if (isset($_POST['q'])) {
		$_GET['q'] = $_POST['q'];
	}

	if (!isset($_GET['q'])) {
		$_GET['q'] = '';
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = '';
	}

	if (!isset($_GET['dir'])) {
		$_GET['dir'] = '';
	}

	$whereQuery = '';

	if (trim($_GET['q']) != '') {
		$whereQuery = " owner_id LIKE '%" . sql_quote($_GET['q']) . "%' ";
	}

	$orderQuery = '';

	switch($_GET['order']) {
		case 'money':
			$orderQuery = "total";
			break;

		case 'sales':
			$orderQuery = "sales";
			break;

		case 'sold':
			$orderQuery = "sold";
			break;

		case 'products':
			$orderQuery = "products";
			break;

		case 'referals':
			$orderQuery = "referals";
			break;

		case 'referal_money':
			$orderQuery = "referal_money";
			break;

		default:
			$orderQuery = "username";
	}

	switch($_GET['dir']) {
		case 'desc':
			$orderQuery .= " DESC";
			abr('orderDir', 'asc');
			break;

		default:
			$orderQuery .= " ASC";
			abr('orderDir', 'desc');
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$cms = new orders();

	if (isset($_GET['purchase_key'])) {
		$data = $cms->getAll(START, LIMIT, " code_achat = '" . $_GET['purchase_key']."' ");
	}
	else {
		$data = $cms->getAll(START, LIMIT, " paid = 'true' ");
	}

	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		
		$member = $membersClass->getAll();
		abr('member', $member);

		abr('foundRows', $cms->foundRows);

		$p = paging('?m=' . $_GET['m'] . '&c=sales&p=', '&q=' . $_GET['q'] . '&order=' . $_GET['order'] . '&dir=' . $_GET['dir'], '', PAGE, LIMIT, $cms->foundRows);
		abr('paging', $p);
	}
	else {
		abr('foundRows', 0);
	}
	abr('data', $data);
?>