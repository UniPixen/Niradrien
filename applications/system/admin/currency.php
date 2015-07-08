<?php
	_setView (__FILE__);
	_setTitle ($langArray['currency']);

	$cms = new system();
	
	if (isset($_POST['save'])) {
		$cms->saveCurrency();
		refresh('?m=' . $_GET['m'] . '&c=' . $_GET['c']);
	}
	
	$data = $cms->getCurrency();
	abr('data', $data);
?>