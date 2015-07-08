<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit_announcement']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=files&id=' . $_GET['id'], 'INVALID ID', 'error');
	}

	require_once ROOT_PATH . '/applications/announcements/modeles/announcements.class.php';
	$announcements = new announcements();

	$announcementData = $announcements->get($_GET['id']);
	
	$announcementType = $announcementData['type'];
	abr('announcementType', $announcementType);
	
	if (isset($_POST['edit'])) {
		$status = $announcements->edit($_GET['id']);
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			if ($_POST['type'] == 'authors') {
				refresh ('?m=' . $_GET['m'] . '&c=list&type=authors', $langArray['edit_complete']);
			}

			else {
				refresh ('?m=' . $_GET['m'] . '&c=list&type=system', $langArray['edit_complete']);
			}
		}
	}

	else {
		$_POST = $announcements->get($_GET['id']);
	}
?>