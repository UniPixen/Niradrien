<?php
	_setView (__FILE__);
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$cms = new members(); 
	
	if (isset($_POST['edit'])) {
		$adminEdit = true;
		
		if (isset($personalEdit)) {
			$adminEdit = false;
		}
		
		$status = $cms->edit($_GET['id'], $adminEdit);
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			if (isset($personalEdit)) {
				refresh ('?m=' . $_GET['m'] . '&c=edit&id=' . $_GET['id'], $langArray['edit_complete']);
			}

			else {
				refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
			}
		}
	}

	else {
		$_POST = $cms->get($_GET['id']);
		$badges = explode(',', $_POST['badges']);
		$_POST['badges'] = array();

		foreach ($badges AS $badge) {
			$_POST['badges'][] = $badge;
		}
	}
	
	$member = $cms->get($_GET['id']);
	$member['stats'] = $cms->getStatistic($_GET['id']);
	abr('member', $member);

	require_once ROOT_PATH . '/applications/' . $_GET['m'] . '/modeles/groups.class.php';
	$g = new groups();
	
	$groups = $g->getAll();
	abr('groups', $groups);	
	
	require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
	$badges = new badges();
	
	$badges_data = $badges->getAll(0, 0, "type = 'other'");
	abr('badges', $badges_data);
	
	if (isset($_POST['badges'])) {
		if (!is_array($_POST['badges'])) {
			$_POST['badges'] = explode(',', $_POST['badges']);
		}
	}
	
	else {
		$_POST['badges'] = array();
	}
?>