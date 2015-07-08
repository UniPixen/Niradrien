<?php
	_setView(__FILE__);

	$key = get_id(1);
	$page = $pagesClass->getByKey($key);

	if (is_array($page)) {
		if ($page['title'] != '') {
			$smarty->assign('title', $page['title']);
		}

		else {
			$smarty->assign('title', $page['name']);
		}

		if ($page['keywords'] != '') {
			$smarty->assign('keywords', $page['keywords']);
		}

		if ($page['description'] != '') {
			$smarty->assign('description', $page['description']);
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}

	abr('page', $page);
?>