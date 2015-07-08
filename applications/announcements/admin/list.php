<?php
	if (!isset($_GET['type']) || !in_array($_GET['type'], array('authors', 'system'))) {
		refresh('?m=' . $_GET['m'] . '&c=list&type=system', '', 'error');
	}

	_setView (__FILE__);
	_setTitle ($langArray['announcements'] . ' - ' . ucfirst($langArray[$_GET['type']]));

	$types = array('authors', 'system');

	$tmp = array();

	foreach($types AS $type) {
		$tmp[] = array (
			'name' => ucfirst($type),
			'href' => '?m=' . $_GET['m'] . '&c=lists&type=' . $type
		);	
	}
	abr('types', $tmp);

	require_once ROOT_PATH . '/applications/announcements/modeles/announcements.class.php';
	$announcements = new announcements();
	
	$data = $announcements->getAll(START, LIMIT, "type='" . $_GET['type'] . "'");
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=list&type=' . $_GET['type'] . '&p=', '', PAGE, LIMIT, $announcements->foundRows);
	abr ('paging', $p);
?>