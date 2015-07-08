<?php
	_setView (__FILE__);
	_setTitle ($langArray['add_language']);

	$cms = new languages();
	
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
		$_POST['code'] = '';
		$_POST['locale'] = '';
		$_POST['locale_territory'] = '';
		$_POST['flag'] = '';
		$_POST['visible'] = true;
	}
?>