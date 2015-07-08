<?php
	require_once ROOT_PATH.'/applications/payments/modeles/payments_abstract.class.php';

	class payza extends payments_abstract {
		public function generateForm($order_data = array()) {
			global $langArray, $currency, $config;

			if ($this->getMeta('payza_sandbox_mode')) {
				$url = 'https://sandbox.alertpay.com/sandbox/payprocess.aspx';
			}

			else {
				$url = 'https://www.alertpay.com/PayProcess.aspx';
			}

			$form  = '<form class="form-for-pay" action="' . $url . '" method="post" id="alertpay_form_submit">'."\n";
			$form .= '<input type="hidden" name="ap_merchant" value="' . $this->getMeta('payza_merchant_id') . '" />'."\n";
			$form .= '<input type="hidden" name="ap_amount" value="' . (float)$order_data['price'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_currency" value="' . $currency['code'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_purchasetype" value="Item" />'."\n";
			$form .= '<input type="hidden" name="ap_itemname" value="Buy ' . htmlspecialchars($order_data['product_name'], ENT_QUOTES, 'utf-8') . ' - #' . (int)$order_data['id'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_itemcode" value="' . (int)$order_data['id'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_returnurl" value="http://' . $config['domain'] . '/account/downloads" />'."\n";
			$form .= '<input type="hidden" name="ap_cancelurl" value="http://' . $config['domain'] . '/product/' . $order_data['product_id'] . '/' . url($order_data['product_name']) . '" />'."\n";
			$form .= '<button id="paypal-purchase-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> '.$langArray['purchase'].'</button>'."\n";
			$form .= '</form>'."\n";

			return $form;
		}

		public function generateDepositForm($order_data = array()) {
			global $langArray, $currency, $config;

			if ($this->getMeta('payza_sandbox_mode')) {
				$url = 'https://sandbox.alertpay.com/sandbox/payprocess.aspx';
			}

			else {
				$url = 'https://www.alertpay.com/PayProcess.aspx';
			}

			$form  = '<form class="form-for-pay" action="' . $url . '" method="post" id="alertpay_form_submit">'."\n";
			$form .= '<input type="hidden" name="ap_merchant" value="' . $this->getMeta('alertpay_merchant_id') . '" />'."\n";
			$form .= '<input type="hidden" name="ap_amount" value="' . (float)$order_data['deposit'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_currency" value="' . $currency['code'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_purchasetype" value="Item" />'."\n";
			$form .= '<input type="hidden" name="ap_itemname" value="' . $langArray['make_deposit'] . ' - #' . (int)$order_data['id'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_itemcode" value="' . (int)$order_data['id'] . '" />'."\n";
			$form .= '<input type="hidden" name="ap_returnurl" value="http://' . $config['domain'] . '/members/deposit/success/' . (int)$order_data['id'] . '/" />'."\n";
			$form .= '<input type="hidden" name="ap_cancelurl" value="http://' . $config['domain'] . '/members/deposit" />'."\n";
			$form .= '<input type="hidden" name="apc_1" value="deposit" /> '."\n";
			$form .= '<button id="paypal-purchase-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> ' . $langArray['purchase'] . '</button>'."\n";
			$form .= '</form>'."\n";

			return $form;
		}
	}
?>