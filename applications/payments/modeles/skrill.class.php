<?php
	require_once ROOT_PATH.'/applications/payments/modeles/payments_abstract.class.php';

	class skrill extends payments_abstract {
		public function generateForm($order_data = array()) {
			global $langArray, $currency, $config;

			$langs = array('EN','DE','ES','FR','IT','PL','GR','RO','RU','TR','CN','CZ','NL','DA','SV','FI');

			$language = 'FR';

			$form  = '<form class="form-for-pay" action="https://www.moneybookers.com/app/payment.pl" method="post" id="moneybookers_form_submit">';
			$form .= '<input type="hidden" name="pay_to_email" value="' . $this->getMeta('skrill_email') . '" />';
			$form .= '<input type="hidden" name="recipient_description" value="' . $this->getMeta('website_title') . '" />';
			$form .= '<input type="hidden" name="transaction_id" value="' . (int)$order_data['id'] . '" />';
			$form .= '<input type="hidden" name="return_url" value="http://' . $config['domain'] . '/account/downloads/" />';
			$form .= '<input type="hidden" name="cancel_url" value="http://' . $config['domain'] . '/product/' . $order_data['product_id'] . '/' . url($order_data['product_name']) . '" />';
			$form .= '<input type="hidden" name="status_url" value="http://' . $config['domain'] . '/payments/skrill/" />';
			$form .= '<input type="hidden" name="pay_from_email" value="' . $this->getMemberData('email') . '" />';
			$form .= '<input type="hidden" name="firstname" value="' . $this->getMemberData('firstname') . '" />';
			$form .= '<input type="hidden" name="lastname" value="' . $this->getMemberData('lastname') . '" />';
			$form .= '<input type="hidden" name="amount" value="' . (float)$order_data['price'] . '" />';
			$form .= '<input type="hidden" name="currency" value="' . $currency['code'] . '" />';
			$form .= '<input type="hidden" name="language" value="' . $language . '" />';
			$form .= '<input type="hidden" name="detail1_text" value="Buy ' . htmlspecialchars($order_data['product_name'], ENT_QUOTES, 'utf-8') . '" />';
			$form .= '<input type="hidden" name="merchant_fields" value="order_id" />';
			$form .= '<input type="hidden" name="order_id" value="' . (int)$order_data['id'] . '" />';
			$form .= '<button id="paypal-purchase-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> ' . $langArray['purchase'] . '</button>';
			$form .= '</form>';

			return $form;
		}

		public function generateDepositForm($order_data = array()) {
			global $langArray, $currency, $config;

			$langs = array('EN','DE','ES','FR','IT','PL','GR','RO','RU','TR','CN','CZ','NL','DA','SV','FI');

			$language = 'FR';

			$form  = '<form class="form-for-pay" action="https://www.moneybookers.com/app/payment.pl?p=IGNITER" method="post" id="moneybookers_form_submit">';
			$form .= '<input type="hidden" name="pay_to_email" value="' . $this->getMeta('skrill_email') . '" />';
			$form .= '<input type="hidden" name="recipient_description" value="' . $this->getMeta('website_title') . '" />';
			$form .= '<input type="hidden" name="transaction_id" value="' . (int)$order_data['id'] . '" />';
			$form .= '<input type="hidden" name="return_url" value="http://' . $config['domain'] . '/members/deposit/success/' . (int)$order_data['id'] . '/" />';
			$form .= '<input type="hidden" name="cancel_url" value="http://' . $config['domain'] . '/members/deposit" />';
			$form .= '<input type="hidden" name="status_url" value="http://' . $config['domain'] . '/payments/deposit_skrill/" />';
			$form .= '<input type="hidden" name="pay_from_email" value="' . $this->getMemberData('email') . '" />';
			$form .= '<input type="hidden" name="firstname" value="' . $this->getMemberData('firstname') . '" />';
			$form .= '<input type="hidden" name="lastname" value="' . $this->getMemberData('lastname') . '" />';
			$form .= '<input type="hidden" name="amount" value="' . (float)$order_data['deposit'] . '" />';
			$form .= '<input type="hidden" name="currency" value="' . $currency['code'] . '" />';
			$form .= '<input type="hidden" name="language" value="' . $language . '" />';
			$form .= '<input type="hidden" name="detail1_text" value="' . $langArray['make_deposit'] . ' - #' . (int)$order_data['id'] . '" />';
			$form .= '<input type="hidden" name="merchant_fields" value="order_id" />';
			$form .= '<input type="hidden" name="order_id" value="' . (int)$order_data['id'] . '" />';
			$form .= '<button id="paypal-purchase-button" class="btn btn-big-shadow" type="submit"><i class="hd-cart"></i> ' . $langArray['purchase'] . '</button>';
			$form .= '</form>';

			return $form;
		}
	}
?>