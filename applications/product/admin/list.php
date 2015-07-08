<?php
	_setView(__FILE__);
	_setTitle($langArray['products']);

	if (isset($_POST['q'])) {
		$_GET['q'] = $_POST['q'];
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_GET['q'])) {
		$_GET['q'] = '';
	}

	if (!isset($_GET['order'])) {
		$_GET['order'] = '';
	}

	if (!isset($_GET['dir'])) {
		$_GET['dir'] = '';
	}

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();

	$whereQuery = '';
	if (trim($_GET['q']) != '') {
		$whereQuery = " AND name LIKE '%" . sql_quote($_GET['q']) . "%' ";
	}

	$orderQuery = '';
	switch($_GET['order']) {
		case 'name':
			$orderQuery = "name";
			break;

		case 'price':
			$orderQuery = "price";
			break;

		case 'sales':
			$orderQuery = "sales";
			break;

		case 'earning':
			$orderQuery = "earning";
			break;

		case 'free':
			$orderQuery = "free_request";
			break;

		case 'freefile':
			$orderQuery = "free_file";
			break;

		case 'weekly':
			$orderQuery = "weekly_to";
			break;

		default:
			$orderQuery = "datetime";
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

	if (isset($_POST['member'])) {
		$_GET['member'] = $_POST['member'];
	}

	if (!isset($_GET['member'])) {
		$_GET['member'] = '';
	}

	if (is_numeric($_GET['member'])) {
		$whereQuery .= " AND member_id = '" . intval($_GET['member']) . "' ";
	}

	$data = $cms->getAll(START, LIMIT, $whereQuery, $orderQuery);
	abr('data', $data);
	abr('foundRows', $cms->foundRows);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '&q=' . $_GET['q'] . '&order=' . $_GET['order'] . '&dir=' . $_GET['dir'] . '&member=' . $_GET['member'], PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();
	$members = $membersClass->getAll(0, 0, '', 'username ASC');
	abr('members', $members);
?>