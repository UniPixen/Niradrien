<?php
	_setView (__FILE__);
	_setTitle ($langArray['the_team']);

	require_once ROOT_PATH . '/applications/help/modeles/team.class.php';
	$cms = new team();

	if (isset($_GET['up']) || isset($_GET['down'])) {
		$cms->tableName = 'team';
		$cms->idColumn = 'member_id';

		if (isset($_GET['up']) && is_numeric($_GET['up'])) {
			$cms->moveUp($_GET['up']);
		}

		elseif (isset($_GET['down']) && is_numeric($_GET['down'])) {
			$cms->moveDown($_GET['down']);
		}
	}

	$data = $cms->getAll(START, LIMIT);
	abr('data', $data);
?>