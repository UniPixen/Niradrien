<?php
	require_once '../../../../config.php';
	require_once $config['root_path'] . '/system/functions.php';

	session_id($_POST['sessID']);

	include_once $config['system_path'] . '/start_system.php';

	if (!check_login_bool()) {
		echo $langArray['error'] . ' : ' . $langArray['invalid_upload'];
		exit (0);
	}

	if (!isset($_FILES['file'])) {
		echo $langArray['error'] . ' : ' . $langArray['invalid_upload'];
		exit (0);
	}

	require_once '../../modeles/files.class.php';

	$filesClass = new files();
	$s = $filesClass->addFile();

	if (is_array($s)) {
		echo json_encode (
			array (
				'status' => 'done',
				'file' => $s
			)
		);
	}

	else {
		echo json_encode (
			array (
				'status' => $s
			)
		);
	}

	exit (0);
?>