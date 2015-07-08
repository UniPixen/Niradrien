<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit_team_member']);

	if (!isset($_GET['member_id']) || !is_numeric($_GET['member_id'])) {
		refresh('?m=' . $_GET['m'] . '&c=team', $langArray['invalid_member_id'], 'error');
	}

	require_once ROOT_PATH . '/applications/help/modeles/team.class.php';
	$cms = new team();
	
	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['member_id']);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=team', $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['member_id']);
	}
	
	$member = $cms->get($_GET['member_id']);
	abr('member', $member);
?>