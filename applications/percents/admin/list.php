<?php
    _setView (__FILE__);
    _setTitle ($langArray['percents']);

	require_once ROOT_PATH . 'applications/percents/modeles/percents.class.php';
    $cms = new percents();

	$data = $cms->getAll();
	abr('data', $data);
?>