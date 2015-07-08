<?php 
	_setView(__FILE__);
	_setLayout("screenshots");
	
	$memberID = get_id(2);
	$productClass = new product();
	$member = $productClass->get($memberID);

	if ($member['status'] == 'deleted') {
		header('Location: http://' . DOMAIN . '/product/' . $memberID);
	}
	
	if (!is_array($member) || (check_login_bool() && $member['status'] == 'unapproved' && $member['member_id'] != $_SESSION['member']['member_id']) || $member['status'] == 'queue' || $member['status'] == 'extended_buy') {
		header('Location: http://' . DOMAIN . '/error');
	}

	abr('member', $member);
?>