<?php
	_setView (__FILE__ );
	_setTitle ($langArray['add']);

	$cms = new system();
	
	if (isset($_POST['add'])) {
		$status = $cms->add();		
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}
?>