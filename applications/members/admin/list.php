<?php
	_setView (__FILE__);
	_setTitle ($langArray['members']);

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

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$cms = new members();

	$whereQuery = '';

	if (trim($_GET['q']) != '') {
		$whereQuery = " username LIKE '%" . sql_quote($_GET['q']) . "%' ";
	}

	$orderQuery = '';

	switch($_GET['order']) {
		case 'money':
			$orderQuery = 'total';
			break;

		case 'sales':
			$orderQuery = 'sales';
			break;

		case 'sold':
			$orderQuery = 'sold';
			break;

		case 'products':
			$orderQuery = 'products';
			break;

		case 'referals':
			$orderQuery = 'referals';
			break;

		case 'referal_money':
			$orderQuery = 'referal_money';
			break;

		default:
			$orderQuery = 'member_id';
	}

	switch($_GET['dir']) {
		case 'desc':
			$orderQuery .= ' DESC';
			abr('orderDir', 'asc');
			break;

		default:
			$orderQuery .= ' ASC';
			abr('orderDir', 'desc');
	}

	$data = $cms->getAll(START, LIMIT, $whereQuery, $orderQuery);

	if (is_array($data)) {
		require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
		$percentsClass = new percents();

		require_once ROOT_PATH . '/applications/members/modeles/balance.class.php';
		$balanceClass = new balance();

		$percents = $percentsClass->getAll();

		foreach ($data as $k => $d) {
			$comision = $percentsClass->getPercentRow($d);
			$data[$k]['commission'] = $comision['percent'];

//			if($data[$k]['commission_percent'] < 1) {
//				foreach($percents as $p) {
//					if($d['sold'] >= $p['from'] && ($d['sold'] < $p['to'] || $p['to'] == '0')) {
//						$data[$k]['commission'] = $p['percent'];
//						break;
//					}
//				}
//			} else {
//				$data[$k]['commission'] = $data[$k]['commission_percent'];
//			}

			$data[$k]['sum'] = $balanceClass->getTotalMemberBalanceByType($d['member_id']);
		}
	}
	abr('data', $data);

	$p = paging('?m=' . $_GET['m'] . '&c=list&p=', '&q=' . $_GET['q'] . '&order=' . $_GET['order'] . '&dir=' . $_GET['dir'], PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>