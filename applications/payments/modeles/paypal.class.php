<?php
	require_once ROOT_PATH . '/applications/payments/modeles/payments_abstract.class.php';
	
	require_once ROOT_PATH . 'applications/payments/modeles/payments.class.php';
	$paymentsClass = new payments();
	$paypal_info = $paymentsClass->get('PayPal');

	class paypal extends payments_abstract {
		public function generateForm($order_data = array()) {
			global $langArray, $currency, $config, $paypal_info;

			$url = ($paypal_info['sandbox'] == 'true') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

			$form  = '<form class="form-for-pay" action="' . $url . '" method="post" id="paypal_form_submit">' . "\n";
				$form .= '<input type="hidden" name="cmd" value="_cart" />' . "\n";
				$form .= '<input type="hidden" name="upload" value="1" />' . "\n";
				$form .= '<input type="hidden" name="charset" value="utf-8" />' . "\n";
				$form .= '<input type="hidden" name="redirect_cmd" value="_xclick" />' . "\n";
				$form .= '<input type="hidden" name="no_note" value="1" />' . "\n";
				$form .= '<input type="hidden" name="rm" value="2" />' . "\n";
				$form .= '<input type="hidden" name="paymentaction" value="sale" />';
				$form .= '<input type="hidden" name="business" value="' . $paypal_info['merchant_id'] . '" />' . "\n";
				$form .= '<input type="hidden" name="item_number_1" value="' . (int)$order_data['id'] . '" />' . "\n";
				$form .= '<input type="hidden" name="item_name_1" value="' . htmlspecialchars($order_data['product_name'], ENT_QUOTES, 'utf-8') . '" />' . "\n";
				$form .= '<input type="hidden" name="amount_1" value="' . (float)$order_data['price'] . '" />' . "\n";
				$form .= '<input type="hidden" name="quantity_1" value="1" />' . "\n";
				$form .= '<input type="hidden" name="currency_code" value="' . $currency['code'] . '" />' . "\n";
				$form .= '<input type="hidden" name="no_shipping" value="1" />' . "\n";
				$form .= '<input type="hidden" name="no_note" value="1" />' . "\n";
				$form .= '<input type="hidden" name="notify_url" value="' . $config['protocol'] . '://' . $config['domain'] . '/payments/paypal/" />' . "\n";
				$form .= '<input type="hidden" name="return" value="' . $config['protocol'] . '://' . $config['domain'] . '/payments/paypal/pdt/" />' . "\n";
				$form .= '<input type="hidden" name="cancel_return" value="' . $config['protocol'] . '://' . $config['domain'] . '/product/' . $order_data['product_id'] . '/' . url($order_data['product_name']) . '" />' . "\n";
				$form .= '<input type="hidden" name="image_url" value="" />' . "\n";
				$form .= '<input type="hidden" name="email" value="' . $this->getMemberData('email') . '" />' . "\n";
				$form .= '<input type="hidden" name="first_name" value="' . $this->getMemberData('firstname') . '" />' . "\n";
				$form .= '<input type="hidden" name="last_name" value="' . $this->getMemberData('lastname') . '" />' . "\n";
				$form .= '<input type="hidden" name="custom" value="' . (int)$order_data['id'] . '" />' . "\n";
				$form .= '<input type="hidden" name="custom2" value="' . ($order_data['extended'] == 'true' ? 'extended' : 'regular') . '" />' . "\n";
				$form .= '<input type="hidden" name="amount" value="' . (float)$order_data['price'] . '" />' . "\n";
				$form .= '<input type="hidden" name="cs" value="0" />' . "\n";
				$form .= '<input type="hidden" name="page_style" value="PayPal" />' . "\n";
				$form .= '<button id="paypal-purchase-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> ' . $langArray['purchase'] . '</button>' . "\n";
			$form .= '</form>' . "\n";

			return $form;
		}

		public function generateDepositForm($order_data = array()) {
			global $langArray, $currency, $config, $paypal_info;

			$url = ($paypal_info['sandbox'] == 'true') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

			$form  = '<form class="form-for-pay" action="' . $url . '" method="post" id="paypal_form_submit">' . "\n";
				$form .= '<input type="hidden" name="cmd" value="_cart" />' . "\n";
				$form .= '<input type="hidden" name="upload" value="1" />' . "\n";
				$form .= '<input type="hidden" name="charset" value="utf-8" />' . "\n";
				$form .= '<input type="hidden" name="redirect_cmd" value="_xclick">' . "\n";
				$form .= '<input type="hidden" name="no_note" value="1" />' . "\n";
				$form .= '<input type="hidden" name="rm" value="2" />' . "\n";
				$form .= '<input type="hidden" name="paymentaction" value="sale" />';
				$form .= '<input type="hidden" name="business" value="' . $paypal_info['merchant_id'] . '" />' . "\n";
				$form .= '<input type="hidden" name="item_name_1" value="' . $langArray['deposit_of'] . ' ' . (float)$order_data['deposit'] . '' . $currency['symbol'] . '" />' . "\n";
				$form .= '<input type="hidden" name="amount_1" value="' . (float)$order_data['deposit'] . '" />' . "\n";
				$form .= '<input type="hidden" name="quantity_1" value="1" />' . "\n";
				$form .= '<input type="hidden" name="currency_code" value="' . $currency['code'] . '" />' . "\n";
				$form .= '<input type="hidden" name="no_shipping" value="1" />' . "\n";
				$form .= '<input type="hidden" name="no_note" value="1" />' . "\n";
				$form .= '<input type="hidden" name="notify_url" value="' . $config['protocol'] . '://' . $config['domain'] . '/payments/deposit_paypal/" />' . "\n";
				$form .= '<input type="hidden" name="return" value="' . $config['protocol'] . '://' . $config['domain'] . '/payments/deposit_paypal/pdt/" />' . "\n";
				$form .= '<input type="hidden" name="cancel_return" value="' . $config['protocol'] . '://' . $config['domain'] . '/account/deposit" />' . "\n";
				$form .= '<input type="hidden" name="image_url" value="" />' . "\n";
				$form .= '<input type="hidden" name="email" value="' . $this->getMemberData('email') . '" />' . "\n";
				$form .= '<input type="hidden" name="first_name" value="' . $this->getMemberData('firstname') . '" />' . "\n";
				$form .= '<input type="hidden" name="last_name" value="' . $this->getMemberData('lastname') . '" />' . "\n";
				$form .= '<input type="hidden" name="custom" value="' . (int)$order_data['id'] . '" />' . "\n";
				$form .= '<input type="hidden" name="amount" value="' . (float)$order_data['deposit'] . '" />' . "\n";
				$form .= '<input type="hidden" name="cs" value="0" />' . "\n";
				$form .= '<input type="hidden" name="page_style" value="PayPal" />' . "\n";
				$form .= '<button id="paypal-deposit-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> ' . $langArray['choose'] . '</button>' . "\n";
			$form .= '</form>' . "\n";

			return $form;
		}
	}
?>