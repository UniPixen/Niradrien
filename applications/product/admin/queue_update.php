<?php
	_setView(__FILE__);
	_setTitle($langArray['queue_update']);

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
    $cms = new product();

	$data = $cms->getAllForUpdate(START, LIMIT);
	abr('data', $data);

	$p = paging ('?m=' . $_GET['m'] . '&c=queue_update&p=', '', PAGE, LIMIT, $cms->foundRows);
	abr ('paging', $p);
?>