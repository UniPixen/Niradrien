<?php 
	_setView(__FILE__);

	$productID = get_id(2);
	$productClass = new product();
	$product = $productClass->get($productID);

	if (is_array($product)) {
		if ($product['status'] == 'active' && !empty($product['demo_url'])) {
			_setLayout('preview');
			
			require_once ROOT_PATH.'/applications/members/modeles/members.class.php';
			$membersClass = new members();
			
			$product['member'] = $membersClass->get($product['member_id']);

			abr('product', $product);
		}

		elseif ($product['status'] == 'deleted') {
			header('Location: http://' . DOMAIN . '/product/' . $productID . '/' . url($product['name']));
		}

		else {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>