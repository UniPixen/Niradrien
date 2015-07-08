<?php
    _setView (__FILE__);
    _setTitle ($langArray['support']);

	if (!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

    require_once ROOT_PATH . '/applications/support/modeles/support.class.php';
    $cms = new support();

    $data = $cms->getAll(START, LIMIT);
    abr('data', $data);

    $p = paging ('?m=' . $_GET['m'] . '&c=list&p=', '', PAGE, LIMIT, $cms->foundRows);
    abr ('paging', $p);
?>