<?php
	_setView(__FILE__);
	_setTitle($langArray['add_attribute_category']);

	require_once ROOT_PATH . '/applications/attributes/modeles/attributes_categories.class.php';
	$cms = new attributes_categories();
	
	if (isset($_POST['add'])) {
		$status = $cms->add();
		
		if ($status !== true) {
			abr('error', $status);
		}

		else {
			refresh('?m=' . $_GET['m'] . '&c=list', $langArray['add_complete']);
		}
	}

	else {
		$_POST['name'] = '';
		$_POST['name_en'] = '';
		$_POST['type'] = '';
		$_POST['not_applicable'] = 'true';
		$_POST['visible'] = 'true';
	}
	
	$mysql->query("
		SELECT *
		FROM categories
		WHERE sub_of = '0'
		ORDER BY order_index ASC
	", __FUNCTION__ );
	
	if ($mysql->num_rows() > 0) {
		while ($d = $mysql->fetch_array()) {
			$categories[$d['id']] = $d;
		}

		abr('categories', $categories);
	}
?>