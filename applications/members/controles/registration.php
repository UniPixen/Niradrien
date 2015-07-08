<?php
	_setView(__FILE__);
	_setTitle($langArray['create_account']);

	if (check_login_bool()) {
		$username = $_SESSION['member']['username'];
		refresh('/member/' . $username);
	}

	if (get_id(2) == 'confirmation') {
		abr('confirmation', 'yes');
	}

	else {
		abr('recaptcha', '<div class="g-recaptcha" data-sitekey="' . $meta['recaptcha_public_key'] . '"></div>');

		if (isset($_POST['add'])) {
			$membersClass = new members();
			$s = $membersClass->add();
			
			if ($s === true) {
				refresh('/register/confirmation');
			}

			else {
				$message = '<ul>';
					foreach($s as $e) {
						$message .= '<li>' . $e . '</li>';
					}
				$message .= '</ul>';
				addErrorMessage($message, '', 'error');
			}
		}

		else {
			$_POST['firstname'] = '';
			$_POST['lastname'] = '';
			$_POST['username'] = '';
			$_POST['email'] = '';
		}
	}

	// Charger les pays
	require_once ROOT_PATH . '/applications/countries/modeles/countries.class.php';
	$countriesClass = new countries();

	$countries = $countriesClass->getAll(0, 0, " visible = 'true' ");
	abr('countries', $countries);
?>