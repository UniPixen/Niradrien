<?php 
	_setView (__FILE__);

	if (!isset($_GET['member_id']) || !is_numeric($_GET['member_id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'ID invalide', 'error');
	}

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$cms = new members();
	$member = $cms->get($_GET['member_id']);

	if (!$member) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}

	_setTitle ($langArray['increase_balance_of'] . ' ' . $member['username']);

	if (isset($_POST['add'])) {
		// Charger le solde
		require_once ROOT_PATH . '/applications/members/modeles/balance.class.php';
		$balanceClass = new balance();

		$status = $balanceClass->add();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=balance&id=' . $_GET['member_id'], $langArray['add_complete']);
		}	
	}
?>