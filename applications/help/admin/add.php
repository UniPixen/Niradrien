<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_member']);

	require_once ROOT_PATH . '/applications/help/modeles/team.class.php';
	$cms = new team();
	
	$teamMembers = $cms->getAll(START, LIMIT);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	// On sélectionne tous les membres qui ne sont pas déjà membres de l'équipe
	$alreadyTeam = '';
	if (is_array($teamMembers)) {
		foreach ($teamMembers as $key => $value) {
			$alreadyTeam .= $key . ', ';
		}
	
		// On enlève la virgule de fin, sinon on aura une erreur
		$alreadyTeam .= substr($alreadyTeam, 0, -2);

		$whereQuery = 'member_id NOT IN (' . $alreadyTeam . ')';
		$orderQuery = 'member_id ASC';

		$members = $membersClass->getAll(START, LIMIT, $whereQuery, $orderQuery);
	}

	else {
		$members = $membersClass->getAll(START, LIMIT);
	}
	
	abr('members', $members);
	
	if (isset($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=team', $langArray['add_complete']);
		}
	}

	else {
		$_POST['member_id'] = '';
		$_POST['role'] = '';
		$_POST['role_en'] = '';
		$_POST['photo'] = '';
	}
?>