<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . '/start_system.php';

	admin_login();

	if (isset($_POST['delete']) && isset($_POST['id']) && isset($_SESSION['member']['access']['attributes'])) {
		require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
		$cms = new attributes_categories();
		$cms->delete(intval($_POST['id']));

		die (
			json_encode(
				array_merge(
					$_POST, array(
						'status' => 'true'
					)
				)
			)
		);
	}

	elseif (isset($_POST['deleteAttr']) && isset ($_POST['id']) && isset($_SESSION['member']['access']['attributes'])) {
		require_once ROOT_PATH . '/applications/attributes/modeles/attributes.class.php';
		$cms = new attributes();
		$cms->delete(intval($_POST['id']));

		die (
			json_encode(
				array_merge(
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