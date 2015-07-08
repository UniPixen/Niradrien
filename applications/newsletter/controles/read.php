<?php
	_setView(__FILE__); 

	if (!isset($_GET['newsletter_id'])) {
		$_GET['newsletter_id'] = '0';
	}

	if (!isset($_COOKIE['newsletter'.$_GET['newsletter_id']])) {
		require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
		$newsletterClass = new newsletter();
		$newsletterClass->incRead($_GET['newsletter_id']);

		setcookie('newsletter' . $_GET['newsletter_id'], 'read', time() + 2592000, '/', '.' . $config['domain']);
	}

	header ('Content-type: image/png');
	$image = imagecreate (1, 1) or die ('image create error');
	$background_color = imagecolorallocate ($image, 255, 255, 255);
	imagepng ($image);
?>