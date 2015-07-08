<?php
	_setView(__FILE__);
	_setTitle($langArray['add']);

	require_once ROOT_PATH . '/applications/quiz/modeles/quiz.class.php';
	$cms = new quiz();

	if (isset($_POST['add'])) {
		$status = $cms->add ();

		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['visible'] = 'true';
	}
?>