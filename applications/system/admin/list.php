<?php
	_setView (__FILE__);
	_setTitle ($langArray['list']);

	$cms = new system();

	$data = $cms->getAll(0, 0);
	
	$tmp = array();
	foreach($data AS $name => $value) {
		$value['help'] = isset($langArray[$value['name']]) ? $langArray[$value['name']] : false;
		$tmp[$name] = $value;
	}
	
	abr('data', $tmp);
?>