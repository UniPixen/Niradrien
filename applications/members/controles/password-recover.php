<?php
	_setView(__FILE__);
	_setTitle($langArray['forgot_password']);

	abr('newPassword', false);

	if (check_login_bool()) {
		refresh('/');
	}

	// ON ENVOI LE MAIL QUI CONTIENT LA CLÉ
	if (isset($_POST['send'])) {
		$membersClass = new members();

		$s = $membersClass->lostPassword();
		if ($s === true) {
			refresh('/password-recover', $langArray['complete_mail_password'], 'complete');
		}

		else {
			addErrorMessage($langArray[$s], '', 'error');
		}
	}

	// SI ON EST SUR LA PAGE QUI CONTIENT LA CLÉ DANS L'URL
	if (isset($_GET['key'])) {
		abr('key', htmlentities($_GET['key']));
		abr('newPassword', true);

		$membersClass = new members();

		// ON VÉRIFIE QUE LA CLÉ EXISTE, SI ELLE EXISTE, ON AFFICHE LE FORMULAIRE POUR CHANGER LE MOT DE PASSE
		$k = $membersClass->getTempPasswordKey($_GET['key']);
		if (isset($k['exist']) === true) {
			// SI, DEPUIS LE FORMULAIRE, ON A CHANGÉ DE MOT DE PASSE
			if (isset($_POST['password_key'])) {
				$s = $membersClass->changeLostPassword($k['member_id']);
				if ($s === true) {
					refresh('/login', $langArray['complete_reset_password'], 'complete');
				}

				else {
					addErrorMessage($langArray[$s], '', 'error');
				}
			}
		}
		else {
			refresh('/password-recover', $langArray['error_invalid_password_key'], 'error');
		}
	}
?>