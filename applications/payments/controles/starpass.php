<?php
	$deposit_id = 0;
	
	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/account/deposit';
		refresh('/login');
	}

	if (isset($_SESSION['tmp']['deposit_id'])) {
		$deposit_id = (int)$_SESSION['tmp']['deposit_id'];
	}

	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();

	$starpass = $paymentsClass->get('StarPass');

	require_once ROOT_PATH . '/applications/members/modeles/deposit.class.php';
	$cms = new deposit();
	$deposit_info = $cms->get($deposit_id);

	$ident = '';
	$idp = '';
	$ids = '';
	$idd = '';
	$codes = '';
	$code1 = '';
	$code2 = '';
	$code3 = '';
	$code4 = '';
	$code5 = '';
	$datas = '';
	$idp = $starpass['merchant_id'];
	
	$idd = $starpass['token'];
	$ident = $idp . ';' . $ids . ';' . $idd;
	
	if (isset($_POST['code1'])) {
		$code1 = $_POST['code1'];
	}
	if (isset($_POST['code2'])) {
		$code2 = ';' . $_POST['code2'];
	}
	if (isset($_POST['code3'])) {
		$code3 = ';' . $_POST['code3'];
	}
	if (isset($_POST['code4'])) {
		$code4 = ';' . $_POST['code4'];
	}
	if (isset($_POST['code5'])) {
		$code5 = ';' . $_POST['code5'];
	}
	
	$codes = $code1 . $code2 . $code3 . $code4 . $code5;
	
	if (isset($_POST['DATAS'])) {
		$datas = $_POST['DATAS'];
	}

	$ident = urlencode($ident);
	$codes = urlencode($codes);
	$datas = urlencode($datas);

	$get_f = file('http://script.starpass.fr/check_php.php?ident=' . $ident . '&codes=' . $codes . '&DATAS=' . $datas);
	
	if (!$get_f) {
		exit ('Votre serveur n\'a pas accès au serveur de StarPass, merci de contacter votre hébergeur.');
	}

	$tab = explode('|', $get_f[0]);

	if (is_array($tab)) {
		if (!$tab[1]) {
			$url = 'http://script.starpass.fr/error.php';
		}

		else {
			$url = $tab[1];
		}

		$pays = $tab[2];
		$palier = urldecode($tab[3]);
		$id_palier = urldecode($tab[4]);
		$type = urldecode($tab[5]);

		// CODES INCORRECTS, ERREUR
		if (substr($tab[0], 0, 3) != 'OUI') {
			refresh('/account/payment', $langArray['wrong_codes'], 'error');
		}
		
		// CODES VALIDÉS
		else {
			$codesArray = explode(';', urldecode($codes));
			if (substr($tab[0], 0, 3) == 'OUI' && count($codesArray) == 5) {
				$cms->depositIsPay($deposit_info['id']);
			}

			refresh('http://' . $config['domain'] . '/account/deposit/success/' . $deposit_info['id'] . '/');
			// echo 'idd : ' . $idd . ' / codes : ' . $codes . ' / datas : ' . $datas . ' / pays : ' . $pays . ' / palier : ' . $palier . ' / id_palier : ' . $id_palier . ' / type : ' . $type;
		}
	}

	else {
		include_once (ROOT_PATH . '/applications/error/controles/index.php');
	}
?>