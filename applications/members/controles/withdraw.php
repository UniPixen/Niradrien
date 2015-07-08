<?php
	_setView(__FILE__);
	_setTitle($langArray['withdrawal']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/withdraw';
		refresh('/login');
	}

	$membersClass = new members();

	$member = $membersClass->get($_SESSION['member']['member_id']);
	abr('member', $member);

	if ($member['author'] != 'true') {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	$date['year'] = date('Y');
	$date['month'] = date('n');
	$date['day'] = date("t");
	abr('date', $date);

	if (isset($_POST['submit'])) {
		require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
		$depositClass = new deposit();
		$s = $depositClass->addWithdraw();
		
		if ($s === true) {
			refresh('/account/withdraw', $langArray['complete_add_withdrawal'], 'complete');
		}

		else {
			foreach($s as $e) {
				$message .= $e;
			}
			addErrorMessage($message, '', 'error');
		}
	}
?>