<?php
	_setView(__FILE__);
	_setTitle($langArray['sign_in']);

	if (check_login_bool()) {
		refresh('/');
	}

	// Si le membre vérifie son adresse email
	if (isset($_GET['command']) && $_GET['command'] == 'activate' && isset($_GET['member']) && isset($_GET['key'])) {
		$membersClass = new members();

		$s = $membersClass->activateMember($_GET['member'], $_GET['key']);

		if ($s === true) {
			refresh('/register/complete');
		}
		
		else {
			addErrorMessage($s['valid'], '', 'error');
		}
	}

	// Connexion
	if (isset($_POST['login'])) {
		$membersClass = new members();
		$login = $membersClass->login();

		if ($login === true) {
			if (isset($_SESSION['temp']['golink'])) {
				$web = $_SESSION['temp']['golink'];
				unset($_SESSION['temp']['golink']);
				refresh($web);
			}

			if (!isset($_SESSION['token'])) {
				$_SESSION['token'] = md5(md5(time() * rand(16, 32)));
			}

			refresh('/');
		}

		else {
			addErrorMessage($langArray[$login], '', 'error');
		}
	}
?>