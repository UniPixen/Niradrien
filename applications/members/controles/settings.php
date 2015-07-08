<?php
	_setView(__FILE__);
	_setTitle($langArray['settings']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/account/settings';
		refresh('/login');
	}

	// PRODUITS DU MEMBRE
	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$productClass = new product();

	$products = $productClass->getAll(0, 0, " status = 'active' AND member_id = '" . intval($_SESSION['member']['member_id']) . "' ");
	abr('products', $products);

	// CHANGEMENT DE MOT DE PASSE
	if (isset($_POST['change_password'])) {
		$membersClass = new members();
		$s = $membersClass->editNewPassword();
		
		if ($s === true) {
			refresh('/account/settings', $langArray['complete_change_password'], 'complete');
		}

		else {
			$message = '<ul>';
			
			foreach ($s as $e) {
				$message .= '<li>' . $e . '</li>';
			}

			$message .= '</ul>';
			addErrorMessage($message, '', 'error');
		}
	}

	// PRODUIT RÉCOMPENSÉ
	if (isset($_POST['feature_save'])) {
		$membersClass = new members();
		$membersClass->editFeatureProduct();
		
		refresh('/account/settings', $langArray['complete_save_feature'], 'complete');
	}

	// AUTEUR EXCLUSIF
	if (isset($_POST['exclusive_false'])) {
		$membersClass = new members();
		$membersClass->editExclusiveAuthor('false');
		
		refresh('/account/settings', $langArray['complete_exclusive_author_off'], 'complete');
	}

	elseif(isset($_POST['exclusive_true'])) {
		$membersClass = new members();
		$membersClass->editExclusiveAuthor('true');
		
		refresh('/account/settings', $langArray['complete_exclusive_author_on'], 'complete');
	}

	// CHANGEMENT DES PRÉFÉRENCES DE LICENCES
	if (isset($_POST['save_license'])) {
		$membersClass = new members();
		$s = $membersClass->editSaveLicense();
		
		if ($s === true) {
			refresh('/account/settings', $langArray['complete_save_license'], 'complete');
		}

		else {
			addErrorMessage($s, '', 'error');
		}
	}

	// CHANGER L'AVATAR ET L'IMAGE DE COUVERTURE
	if (isset($_POST['change_avatar_image'])) {
		$membersClass = new members();
		$membersClass->editChangeAvatarImage();
		
		$message = '';
		
		if ($membersClass->avatarError) {
			$message .= '<li>' . $membersClass->avatarError . '</li>';
		}

		if ($membersClass->homeimageError) {
			$message .= '<li>' . $membersClass->homeimageError . '</li>';
		}

		if ($message != '') {
			$message = '<ul>' . $message . '</li>';
			addErrorMessage($message, '', 'error');
		}

		else {
			refresh('/account/settings', $langArray['complete_change_avatar_image'], 'complete');
		}
	}

	// SAUVEGARDER LES INFORMATIONS PERSONNELLES
	if (isset($_POST['personal_edit'])) {
		$membersClass = new members();
		$s = $membersClass->editPersonalInformation();
		
		if ($s === true) {
			refresh('/account/settings', $langArray['complete_update_personal_info'], 'complete');
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
		$_POST['firstname'] = $_SESSION['member']['firstname'];
		$_POST['lastname'] = $_SESSION['member']['lastname'];
		$_POST['email'] = $_SESSION['member']['email'];
		$_POST['company_name'] = $_SESSION['member']['company_name'];
		$_POST['profile_title'] = $_SESSION['member']['profile_title'];
		$_POST['profile_desc'] = $_SESSION['member']['profile_desc'];
		$_POST['live_city'] = $_SESSION['member']['live_city'];
		$_POST['country_id'] = $_SESSION['member']['country_id'];
		$_POST['freelance'] = $_SESSION['member']['freelance'];
	}

	require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
	$social = new social();

	$getSocial = $social->getAll(START, LIMIT, " url != '' AND visible = 'true' ");
	abr('getSocial', $getSocial);

	// SAUVEGARDER LES INFORMATIONS DES RÉSEAUX SOCIAUX
	if (isset($_POST['social_edit'])) {
		$membersClass = new members();
		$s = $membersClass->editSocialInformation();
		
		if ($s === true) {
			refresh('/account/settings', $langArray['complete_update_personal_info'], 'complete');
		}

		else {
			addErrorMessage($s, '', 'error');
		}
	}

	else {
		foreach ($getSocial as $social) {
			$_POST['social'][strtolower($social['name'])] = $_SESSION['member']['social'][strtolower($social['name'])];
		}
	}

	// CHARGER LES PAYS
	require_once ROOT_PATH.'/applications/countries/modeles/countries.class.php';
	$countriesClass = new countries();

	$countries = $countriesClass->getAll(0, 0, " visible = 'true' ");
	abr('countries', $countries);
?>