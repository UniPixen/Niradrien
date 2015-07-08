<?php
	_setView(__FILE__);
	$collectionID = get_id(2);
	$collectionsClass = new collections();
	$collection = $collectionsClass->get($collectionID);

	if (!is_array($collection) || ($collection['public'] == 'false' && check_login_bool() && $collection['member_id'] != $_SESSION['member']['member_id'])) {
		refresh('/' . 'collections/', $langArray['wrong_collection'], 'error');
	}

	if (isset($_POST['rating'])) {
		$_GET['rating'] = $_POST['rating'];
	}

	if (!isset($_GET['rating']) || !is_numeric($_GET['rating']) || $_GET['rating'] > 5) {
		$_GET['rating'] = 5;
	}

	elseif($_GET['rating'] < 1) {
		$_GET['rating'] = 1;
	}

	$collection = $collectionsClass->rate($collectionID, $_GET['rating']);
	$stars = '';

	for ($i = 1; $i < 6; $i++) {
		if ($collection['rating'] >= $i) {
			$stars .= '<i class="hd-star on"></i>';
		}

		else {
			$stars .= '<i class="hd-star off"></i>';
		}
	}

	header('Location: http://' . DOMAIN . '/collections/view/' . $collectionID);
?>