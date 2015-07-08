<?php
	_setView(__FILE__);
	_setTitle($langArray['downloads']);

	if (!check_login_bool()) {
		$_SESSION['temp']['golink'] = '/account/downloads';
		refresh('/login');
	}

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$code_achat = get_id(2);
	$getProductID = $ordersClass->buyedProductID($code_achat);
	$productID = $productClass->get($getProductID);

	require_once ROOT_PATH . 'applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
	abr('members', $members);

	if ($code_achat) {
		require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
		$productClass = new product();

		$product = $productClass->get($getProductID);
		if (!is_array($product) || (check_login_bool() && $product['status'] == 'unapproved' && $product['member_id'] != $_SESSION['member']['member_id']) || $product['status'] == 'queue') {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}

		if (isset($_POST['rating'])) {
			$_GET['rating'] = $_POST['rating'];
		}

		if (isset($_GET['rating'])) {
			if (!isset($_GET['rating']) || !is_numeric($_GET['rating']) || $_GET['rating'] > 5) {
				$_GET['rating'] = 5;
			}
			elseif ($_GET['rating'] < 1) {
				$_GET['rating'] = 1;
			}

			$product = $productClass->rate($productID, $_GET['rating']);

			$stars = '';
			for ($i = 1; $i < 6; $i++) {
				if ($product['rating'] >= $i) {
					$stars .= '<i class="hd-star on"></i>';
				}
				else {
					$stars .= '<i class="hd-star off"></i>';
				}
			}

			header('Location: http://' . DOMAIN . '/account/downloads');
		}

		if (isset($_GET['certificate'])) {
			if ($ordersClass->isBuyedDownload($code_achat)) {
				if ($ordersClass->row['extended'] == 'true') {
					$licence = $langArray['extended_licence'];
				}

				else {
					$licence = $langArray['regular_licence'];
				}

				$nom_product = $product['name'];
				$membersClass = new members();
				$member = $membersClass->get($product['member_id']);

				require_once ROOT_PATH . '/system/classes/fpdf.class.php';
				class PDF extends FPDF {
					function Header() {
						global $meta;
						global $ordersClass;
						global $langArray;
						
						$this->SetTitle($meta['website_title'] . ' - ' . $langArray['license_certificate'] . ' #' . $ordersClass->row['id'], true);
						$this->AddFont('hadriendesign', '');
						$this->AddFont('Avenir', '');
						
						$this->SetFillColor(222, 222, 222);	
						$this->Rect(0, 0, $this->w, $this->h, 'F');

						$this->SetFillColor(66, 66, 66);	
						$this->Rect(0, 0, 210, 43, 'F');

						$this->SetFillColor(255, 255, 255);	
						$this->SetDrawColor(222, 222, 222);	
						$this->Rect(30, 15, 150, 267, 'FD');

						$this->SetY(30);
						$this->SetTextColor(175, 210, 61);
						$this->SetFont('hadriendesign', '', 20);
						$this->Cell(7, '', iconv('UTF-8', 'CP1252', 'a'), 0, 1, 'L');

						$this->SetY(30);
						$this->SetX(50);
						$this->SetTextColor(66, 66, 66);
						$this->SetFont('Avenir', '', 20);
						$this->Cell(55, '', iconv('UTF-8', 'CP1252', 'Thesis'), 0, 1, 'L');
						
						$this->SetX(72);
						$this->SetTextColor(175, 210, 61);
						$this->SetFont('Avenir', '', 20);
						$this->Cell(55, '', iconv('UTF-8', 'CP1252', 'Fusion'), 0, 1, 'L');

						$this->SetY(30);
						$this->SetTextColor(191, 191, 191);
						$this->SetFont('Avenir', '', 8);
						$this->Cell('', '', iconv('UTF-8', 'CP1252', $langArray['license_certificate'] . ' #' . $ordersClass->row['id']), 0, 1, 'R');

						$this->SetLineWidth(.15);
						$this->Line('40', '43', '170', '43');
					}

					function cle($cle) {
						$this->Cell(40, '', iconv('UTF-8', 'CP1252', $cle), 0, 0, 'L');
						$this->y0 = $this->GetY();
					}

					function valeur($valeur) {
						$this->Cell(0, '', iconv('UTF-8', 'CP1252', $valeur), 0, 1, 'L');
						$this->y0 = $this->GetY();
					}

					function valeur_product($valeur_product) {
						global $config;
						global $ordersClass;

						$this->Cell(0, '', iconv('UTF-8', 'CP1252', $valeur_product), 0, 1, 'L', '', 'http://' . $config['domain'] . '/product/' . $ordersClass->row['product_id'] . '/' . smarty_modifier_url($ordersClass->row['product_name']));
						$this->y0 = $this->GetY();
					}

					function afficher($cle, $valeur) {
						$this->SetTextColor(0, 0, 0);
						$this->SetFont('Avenir', '', 8);
						$this->cle($cle);
						$this->valeur($valeur);
						$this->Ln(8);
					}

					function pseudo($pseudo) {
						$this->Cell(0, '', iconv('UTF-8', 'CP1252', $pseudo) , 0, 1, 'L');
						$this->y0 = $this->GetY();
					}

					function prenom_nom($prenom_nom) {
						$this->Ln(5);
						$this->SetX(80);
						$this->Cell(0, '', iconv('UTF-8', 'CP1252', $prenom_nom) , 0, 1, 'L');
						$this->y0 = $this->GetY();
					}

					function email($email) {
						$this->Ln(5);
						$this->SetX(80);
						$this->Cell(0, '', iconv('UTF-8', 'CP1252', $email), 0, 1, 'L');
						$this->y0 = $this->GetY();
					}

					function afficher_acheteur($cle, $pseudo, $prenom_nom, $email) {
						$this->SetTextColor(0, 0, 0);
						$this->SetFont('Avenir', '', 8);
						$this->cle($cle);
						$this->pseudo($pseudo);
						$this->prenom_nom($prenom_nom);
						$this->email($email);
						$this->Ln(8);
					}

					function afficher_product($cle, $valeur_product) {
						$this->SetTextColor(0, 0, 0);
						$this->SetFont('Avenir', '', 8);
						$this->cle($cle);
						$this->SetTextColor(175, 210, 61);
						$this->valeur_product($valeur_product);
						$this->Ln(8);
					}

					function Footer() {
						global $config;
						global $meta;

						$this->SetY(-35);
						$this->SetTextColor(191, 191, 191);
						$this->SetFont('Avenir', '', 8);
						$this->Cell(0, 10, iconv('UTF-8', 'CP1252', $meta['website_title'] . ' - ' . $config['domain']), 0, 0, 'C');
					}
				}

				$pdfClass = new PDF('P', 'mm', 'A4');
				$pdfClass->SetMargins(40, 26, 40);
				$pdfClass->AddPage();

				$payment_day = date_format(date_create_from_format('Y-n-j G:i:s', $ordersClass->row['paid_datetime']), 'j');
				$payment_month = date_format(date_create_from_format('Y-n-j G:i:s', $ordersClass->row['paid_datetime']), 'n');
				$payment_year = date_format(date_create_from_format('Y-n-j G:i:s', $ordersClass->row['paid_datetime']), 'Y');

				$pdfClass->SetY(55);
				$pdfClass->afficher($langArray['certificate_date'], date('j') . ' ' . strtolower($langArray['monthArr'][date('n')]) . ' ' . date('Y'));
				$pdfClass->afficher($langArray['payment_date'], $payment_day . ' ' . strtolower($langArray['monthArr'][date($payment_month)]) . ' ' . $payment_year);
				$pdfClass->afficher($langArray['licence_type'], $licence);
				$pdfClass->afficher_acheteur($langArray['purchased'], $_SESSION['member']['username'], $_SESSION['member']['firstname'] . ' ' . $_SESSION['member']['lastname'], $_SESSION['member']['email']);
				$pdfClass->afficher($langArray['seller'], $member['username']);
				$pdfClass->afficher_product($langArray['purchased_product'], $ordersClass->row['product_name']);
				$pdfClass->afficher($langArray['product_id'], $ordersClass->row['product_id']);
				$pdfClass->afficher($langArray['paid_price'], money_format('%i', $ordersClass->row['price']));
				$pdfClass->afficher($langArray['purchase_key'], $ordersClass->row['code_achat']);
				$pdfClass->afficher(‘yourtext’, ‘othertext’);
				
				$pdfClass->Output($meta['website_title'] . ' - ' . $langArray['license_certificate'] . ' #' . $ordersClass->row['id'] . '.pdf', 'D');
				die();
			}

			else {
				include_once (ROOT_PATH . '/applications/error/controles/index.php');
			}
		}

		if (($ordersClass->buyedProductID($code_achat) || $product['free_file'] == 'true') && $product['status'] == 'active') {
			if (file_exists(DATA_SERVER_PATH . 'uploads/products/' . $product['id'] . '/' . $product['main_file'])) {
				$mysql->query("
					UPDATE orders
					SET downloaded = 'true'
					WHERE member_id = '" . $_SESSION['member']['member_id'] . "' && product_id = '" . $ordersClass->row['product_id'] . "' && paid = 'true'
				");

				$nom_product = $product['name'];

				$fileInfo = pathinfo(DATA_SERVER_PATH . 'uploads/products/' . $product['id'] . '/' . $product['main_file']);

				$mimeTypes = array (
					'zip' => 'application/zip'
				);

				if (isset($mimeTypes[$fileInfo['extension']])) {
					header('Content-Type: ' . $mimeTypes[$fileInfo['extension']]);
				}
				else {
					header('Content-Type: application/octet-stream');
				}

				header('Content-Disposition: attachment; filename="hadriendesign-' . $product['id'] . '-' . smarty_modifier_url($nom_product). '-' . $_SESSION['member']['member_id'] . '.zip"');
				header("Content-Length:" . filesize(DATA_SERVER_PATH . '/uploads/products/' . $product['id'] . '/' . $product['main_file']));
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Content-Description: ' . $config['domain'] . ' Download');
				@ob_clean();
				@flush();

				readfile(DATA_SERVER_PATH . '/uploads/products/' . $product['id'] . '/' . $product['main_file']) or die('ERROR !');

				die();
			}
			else {
				addErrorMessage($langArray['downloads_file_not_found'], '', 'error');
			}
		}
		
		else {
			include_once (ROOT_PATH . '/applications/error/controles/index.php');
		}
	}

	$product = $ordersClass->getAllBuyed(" o.member_id = '" . intval($_SESSION['member']['member_id']) . "' AND o.paid = 'true' AND o.type = 'buy'");
	abr('product', $product);

	if ($product) {
		abr('countPurchases', count($product));
	}
	
	else {
		abr('countPurchases', 0);
	}

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	$categories = $categoriesClass->getAll();
	abr('categories', $categories);

	$ratedProducts = $productClass->getRates(str_replace('id', 'product_id', $ordersClass->whereQuery));
	abr('ratedProducts', $ratedProducts);
?>