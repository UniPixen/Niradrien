<?php
	_setView (__FILE__);
	_setTitle ($langArray['add'] . ' ' . $langArray['country']);

	require_once ROOT_PATH . '/applications/countries/modeles/countries.class.php';
	$cms = new countries();
	
	if (isset($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['name_en'] = '';
		$_POST['europe'] = 'false';
		$_POST['visible'] = 'true';
	}
?>