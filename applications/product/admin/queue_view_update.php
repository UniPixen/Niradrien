<?php 
	_setView(__FILE__);
	_setTitle($langArray['queue']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=queue_update', 'WRONG ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if(!isset($_POST['action'])) {
		$_POST['action'] = '';
	}

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();
	
	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$data = $cms->getForUpdate($_GET['id']);
	abr('data', $data);

	$product = $cms->get($data['product_id']);

	if (!is_array($product)) {
		refresh('?m='.$_GET['m'].'&c=queue_update', 'WRONG ID', 'error');
	}

	$product['member'] = $membersClass->get($product['member_id']);
	abr('product', $product);

	if (isset($_POST['submit'])) {
		if ($_POST['action'] == 'approve') {
			$s = $cms->approveUpdate($_GET['id']);
			if ($s === true) {
				refresh('?m=' . $_GET['m'] . '&c=queue_update&p=' . $_GET['p'], $langArray['complete_approve_product_update']);
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}

		elseif ($_POST['action'] == 'delete') {
			$s = $cms->unapproveDeleteUpdate($_GET['id']);
			if ($s === true) {
				refresh('?m=' . $_GET['m'] . '&c=queue_update&p=' . $_GET['p'], $langArray['complete_delete_product_update']);
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}
	}
?>