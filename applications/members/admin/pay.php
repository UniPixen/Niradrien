<?php
	_setView(__FILE__);
	_setTitle($langArray['payout']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=withdraws', $langArray['invalid_member_id'], 'error');
	}

	require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
	$cms = new deposit();
	$data = $cms->getWithdraw($_GET['id']);
	abr('data', $data);
	
	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();
	
	$member = $membersClass->get($data['member_id']);
	abr('member', $member);

	if (isset($_POST['edit'])) {
		$status = $cms->payoutWithdraw();
		
		if ($status !== true) {			
			addErrorMessage($status, '', 'error');
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=withdraws', $langArray['complete_withdraw']);
		}
	}

	else {
		$_POST = $data;
	}
?>