<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';

	admin_login();

	if (isset($_POST['delete']) && isset($_POST['id']) && isset($_SESSION['member']['access']['forum'])) {
		require_once ROOT_PATH . '/applications/forum/modeles/forum.class.php';
		$cms = new forum();
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

	die ();
?>