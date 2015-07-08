<?php
	_setView(__FILE__);
	_setTitle($langArray['contact_support']);

	// CHARGER LES CATEGORIES
	require_once ROOT_PATH . 'applications/support/modeles/support_categories.class.php';
	$categoriesClass = new support_categories();
	$categories = $categoriesClass->getAll(0, 0, " visible = 'true'");
	abr('categories', $categories);

	// ENVOYER LE MESSAGE DE CONTACT
	if (isset($_POST['submit'])) {
		require_once ROOT_PATH . 'applications/support/modeles/support.class.php';
		$supportClass = new support();
		$s = $supportClass->add();

		if ($s === true) {
			refresh('/support/', $langArray['complete_send_email'], 'complete');
		}

		else {
			addErrorMessage($langArray['error_all_fields_required'], '', 'error');
		}
	}
?>