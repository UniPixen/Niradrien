<?php
	_setView(__FILE__);
	_setTitle($langArray['forgot_username']);


	if (check_login_bool()) {
		refresh('/account/settings');
	}

	if (isset($_POST['send'])) {
		$membersClass = new members();
		$s = $membersClass->lostUsername();
		
		if ($s === true) {
			refresh('/username-recover', $langArray['complete_send_username'], 'complete');
		}

		else {
			addErrorMessage($langArray[$s], '', 'error');
		}
	}
?>