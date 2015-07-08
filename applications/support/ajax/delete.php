<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';

	admin_login();

	if (isset($_POST['deleteMail']) && isset($_POST['id']) && isset($_SESSION['member']['access']['support'])) {
		require_once ROOT_PATH . '/applications/support/modeles/support.class.php';
		$cms = new support();
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

	elseif (isset($_POST ['deleteCategory']) && isset ($_POST ['id']) && isset($_SESSION['member']['access']['support'])) {
		require_once ROOT_PATH . '/applications/support/modeles/support_categories.class.php';
		$cms = new support_categories();
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

	echo json_encode (
		array_merge (
			$_POST, array (
				'status' => 'unknown error'
			)
		)
	);

	die ();
?>