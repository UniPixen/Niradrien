<?php 
	_setView(__FILE__);
	_setLayout('screenshots');

	$productID = get_id(2);
	$productClass = new product();
	$product = $productClass->get($productID);

	if (!is_array($product) || (check_login_bool() && $product['status'] == 'unapproved' && $product['member_id'] != $_SESSION['member']['member_id']) || $product['status'] == 'queue' || $product['status'] == 'extended_buy') {
		die();
	}
	abr('product', $product);

	$files = scandir(DATA_SERVER_PATH . '/uploads/products/' . $productID . '/preview/');
	$previewFiles = array();

	if (is_array($files)) {
		foreach( $files as $f) {
			if (file_exists(DATA_SERVER_PATH . '/uploads/products/' . $productID . '/preview/' . $f)) {
				$fileInfo = pathinfo(DATA_SERVER_PATH . '/uploads/products/' . $productID . '/preview/' . $f);
				if (isset($fileInfo['extension']) && (strtolower($fileInfo['extension']) == 'jpg' || strtolower($fileInfo['extension']) == 'png')) {
					$previewFiles[] = $f;
				}
			}
		}
	}

	abr('previewFiles', $previewFiles);
?>