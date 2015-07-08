<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';
	require_once ROOT_PATH . '/system/languagues/fr.php';

	if (!isset($_POST['username']) || trim($_POST['username']) == '') {
		die('
			jQuery("#suggestion_result_container").html("<div class=\"box-warning\">'.$langArray['error_not_set_username'].'</div>");
			jQuery("#ajax_username_checking").css("display", "none");
		');
	}

	if (!preg_match('/^[a-zA-Z0-9_]+$/i', $_POST['username'])) {
		die('
			jQuery("#suggestion_result_container").html("<div class=\"box-error\">'.$langArray['error_not_valid_username'].'</div>");
			jQuery("#ajax_username_checking").css("display", "none");
		');
	}

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	if ($membersClass->isExistUsername($_POST['username'])) {
		die('
			jQuery("#suggestion_result_container").html("<div class=\"box-error\">'.$langArray['error_exist_username'].'</div>");
			jQuery("#ajax_username_checking").css("display", "none");
		');
	}


	die('
		jQuery("#suggestion_result_container").html("<div class=\"box-success \">'.$langArray['error_free_username'].'</div>");
		jQuery("#ajax_username_checking").css("display", "none");
	');
?>