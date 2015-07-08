<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';

	admin_login();

	if (isset($_POST['deleteGroup']) && isset($_POST['id']) && isset($_SESSION['member']['access']['newsletter'])) {
		require_once ROOT_PATH . '/applications/newsletter/modeles/newsletterGroups.class.php';
		$cms = new newsletterGroups();
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

	if (isset($_POST['deleteSEmail']) && isset ($_POST['id']) && isset($_SESSION['member']['access']['newsletter'])) {
		require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
		$cms = new newsletter();
		$cms->deleteSEmail(intval($_POST['id']));

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