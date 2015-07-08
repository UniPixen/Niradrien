<?php
	require_once '../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';
	include_once $config['system_path'] . "/start_system.php";

	admin_login();

	if (isset($_POST['deleteUser']) && isset ($_POST['id']) && isset($_SESSION['member']['access']['members'])) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$cms = new members();
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

	elseif (isset ($_POST['deleteUserGroup']) && isset ($_POST['id']) && isset($_SESSION['member']['access']['members'])) {
		require_once ROOT_PATH . '/applications/members/modeles/groups.class.php';
		$cms = new groups();

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

	elseif (isset($_POST['deleteWithdraw']) && isset($_POST['id']) && isset($_SESSION['member']['access']['members'])) {
		require_once ROOT_PATH . "/applications/members/modeles/deposit.class.php";
		$cms = new deposit();

		$cms->deleteWithdraw(intval($_POST['id']));
		
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

	elseif (isset($_POST['deleteComment']) && isset($_POST['id']) && isset($_SESSION['member']['access']['members'])) {
		require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
		$cms = new comments();

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

	elseif (isset($_POST['deleteBalance']) && isset($_POST['id']) && isset($_SESSION['member']['access']['members'])) {
		require_once ROOT_PATH . "/applications/members/modeles/balance.class.php";
		$cms = new balance();

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