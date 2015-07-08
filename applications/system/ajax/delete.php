<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';

	admin_login();

	if (isset($_POST['deleteKey']) && isset($_POST['id']) && isset($_SESSION['member']['access']['system'])) {
		require_once ROOT_PATH . '/applications/system/modeles/system.class.php';
		$cms = new system();
		$cms->delete(intval($_POST['id']));

		die (
			json_encode (
				array_merge (
					$_POST, array (
						'status' => 'true'
					)
				)
			)
		);
	}

	elseif (isset($_POST['deleteRow']) && isset($_POST['id']) && isset($_SESSION['member']['access']['system'])) {
		require_once ROOT_PATH . '/applications/system/modeles/badges.class.php';
		$badges = new badges();

		$badges->delete(intval($_POST['id']));
		die (
			json_encode (
				array_merge (
					$_POST, array (
						'status' => 'true'
					)
				)
			)
		);
	}

	elseif (isset($_POST['deleteSocialRow']) && isset($_POST['id']) && isset($_SESSION['member']['access']['system'])) {
		require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
		$social = new social();

		$social->delete(intval($_POST['id']));
		die (
			json_encode (
				array_merge (
					$_POST, array (
						'status' => 'true'
					)
				)
			)
		);
	}

	echo json_encode (
		array_merge (
			$_POST, array (
				'status' => 'unknown error'
			)
		)
	);

	die ();
?>