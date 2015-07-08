<?php
	_setView(__FILE__);
	_setTitle($langArray['become_author']);
	_setDescription($langArray['welcome_marketplace_1'] . ' ' . $meta['website_title'] . ', ' . $langArray['welcome_marketplace_2']);

	require_once ROOT_PATH . 'applications/percents/modeles/percents.class.php';
	$percentsClass = new percents();

	$percents = $percentsClass->getAll();

	abr('percents', $percents);

	// DEMANDE D'INVITATION
	$membersClass = new members();

	if (check_login_bool()) {
		$member_email = $_SESSION['member']['email'];
		abr('member_email', $member_email);
	}

	if (check_login_bool() && $_SESSION['member']['author'] == 'true') {
		refresh('/author');
	}

	if (check_login_bool() && isset($_POST['demande_invitation'])) {
		require_once SYSTEM_PATH . '/classes/email.class.php';
		$emailClass = new email();

		if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_SESSION['member']['email'])) {
			if (!empty($_POST['liens']) && !empty($_POST['pourquoi'])) {
				$liens = htmlspecialchars($_POST['liens']);
				$pourquoi = htmlspecialchars($_POST['pourquoi']);

				$emailClass->fromEmail = $_SESSION['member']['email'];
				$emailClass->subject = $langArray['author_invitation_request'];

				$emailClass->message = emailTemplate(
					$langArray['become_author'],
					'
						<p>' . $liens . '</p>
						<p>' . $pourquoi . '</p>
					',
					'',
					'',
					$langArray['email_no_spam']
				);

				$emailClass->to($support_email);
				$emailClass->send();

				refresh('/become-author', $langArray['request_invitation_sent'], 'complete');

				return true;
			}

			else {
				addErrorMessage($langArray['error_liens_pourquoi'], '', 'error');
				return false;
			}
		}

		else {
		    addErrorMessage($langArray['error_not_valid_email'], '', 'error');
		    return false;
		}
	}
?>