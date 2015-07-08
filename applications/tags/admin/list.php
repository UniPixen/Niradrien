<?php
    _setView (__FILE__);
    _setTitle ($langArray['list']);

	require_once ROOT_PATH . '/applications/tags/modeles/tags.class.php';
    $cms = new tags();

	$data = $cms->getAll();
	abr('data', $data);
?>