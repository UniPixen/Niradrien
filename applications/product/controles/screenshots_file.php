<?php 
	_setView(__FILE__);
	_setLayout('screenshots');

	$memberID = get_id(2);
	$productClass = new product();
	$member = $productClass->get($memberID);

	if (!is_array($member) || (check_login_bool() && $member['status'] == 'unapproved' && $member['member_id'] != $_SESSION['member']['member_id']) || $member['status'] == 'queue' || $member['status'] == 'extended_buy') {
		die();
	}
	abr('member', $member);

	if (!isset($_GET['index']) || !is_numeric($_GET['index'])) {
		$_GET['index'] = 0;
	}

	$files = scandir(DATA_SERVER_PATH . '/uploads/products/' . $memberID . '/preview/');
	$previewFiles = array();

	if (is_array($files)) {
		foreach ($files as $f) {
			if (file_exists(DATA_SERVER_PATH . '/uploads/products/' . $memberID . '/preview/' . $f)) {
				$fileInfo = pathinfo(DATA_SERVER_PATH . '/uploads/products/' . $memberID . '/preview/' . $f);
				if (isset($fileInfo['extension']) && (strtolower($fileInfo['extension']) == 'jpg' || strtolower($fileInfo['extension']) == 'png')) {
					$previewFiles[] = $f;
				}
			}
		}
	}

	if (isset($previewFiles[$_GET['index']])) {
		abr('previewFile', $previewFiles[$_GET['index']]);
	}
?>