<?php
	_setView (__FILE__);
	_setTitle (ucfirst($_GET['c']));

	$payments = scandir(dirname(dirname(__FILE__)) . '/controles/');

	if (!in_array($_GET['c'] . '.php', $payments)) {
		refresh ('/' . adminURL . '/?m=' . $_GET['m'] . '&c=list');
	}

	$key = $_GET['c'];

	$form = isset($_POST['form']) ? $_POST['form'] : array();

	if (isset($_POST['edit'])) {
		$cms = new system();
		$cms->editGroup($key, $form);
		refresh ('?m=' . $_GET['m'] . '&c=list', $langArray['edit_complete']);
	}
	abr('group', $key);

	abr('statuses', array(
		0 => $langArray['unactive'],
		1 => $langArray['active']
	));

	if (isset($form[$key . '_status'])) {
		abr('status', $form[$key . '_status']);
	}

	else {
		abr('status', isset($meta[$key . '_status']) ? $meta[$key . '_status'] : 0);
	}

	if (isset($form[$key . '_email'])) {
		abr('email', $form[$key . '_email']);
	}

	else {
		abr('email', isset($meta[$key . '_email']) ? $meta[$key . '_email'] : '');
	}

	if (isset($form[$key . '_secret'])) {
		abr('secret', $form[$key . '_secret']);
	}

	else {
		abr('secret', isset($meta[$key . '_secret']) ? $meta[$key . '_secret'] : '');
	}

	if (isset($form[$key . '_sort_order'])) {
		abr('sort_order', $form[$key . '_sort_order']);
	}

	else {
		abr('sort_order', isset($meta[$key . '_sort_order']) ? $meta[$key . '_sort_order'] : 0);
	}

	if (isset($form[$key . '_logo'])) {
		abr('logo', $form[$key . '_logo']);
	}

	else {
		abr('logo', isset($meta[$key . '_logo']) ? $meta[$key . '_logo'] : 0);
	}
?>