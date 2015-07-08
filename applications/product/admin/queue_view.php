<?php 
	_setView(__FILE__);
	_setTitle($langArray['queue']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=queue', 'WRONG ID', 'error');
	}

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	if (!isset($_POST['action'])) {
		$_POST['action'] = '';
	}

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$cms = new product();

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$data = $cms->get($_GET['id'], false);
	$data['member'] = $membersClass->get($data['member_id']);
	abr('data', $data);

	if (isset($_POST['submit'])) {
		if ($_POST['action'] == 'approve') {
			$s = $cms->approve($_GET['id']);

			if ($s === true) {
				refresh('?m=' . $_GET['m'] . '&c=queue&p=' . $_GET['p'], $langArray['complete_approve_product']);
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}

		elseif ($_POST['action'] == 'unapprove') {
			$s = $cms->unapprove($_GET['id']);

			if ($s === true) {
				refresh('?m=' . $_GET['m'] . '&c=queue&p=' . $_GET['p'], $langArray['complete_unapprove_product']);
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}

		elseif ($_POST['action'] == 'delete') {
			$s = $cms->unapproveDelete($_GET['id']);

			if ($s === true) {
				refresh('?m=' . $_GET['m'] . '&c=queue&p=' . $_GET['p'], $langArray['complete_delete_product']);
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}
	}

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();
	$categories = $categoriesClass->getAll();
	abr('categories', $categories);
?>