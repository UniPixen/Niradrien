<?php
	_setView (__FILE__);
	_setTitle ($langArray['view']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'WRONG ID', 'error');
	}

	require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
	$cms = new newsletter();

	$data = $cms->get($_GET['id']);

	if ($data['send_to'] == 'city') {
		$cities = loadCities();
		$data['send_city'] = $cities[$data['send_id']]['name'];
	}
	
	elseif ($data['send_to'] == 'group') {
		$newsletterGroupsClass = new newsletterGroups();

		$bGroup = $newsletterGroupsClass->get($data['send_id']);
		$data['send_group'] = $bGroup['name'];
	}

	abr('data', $data);
?>