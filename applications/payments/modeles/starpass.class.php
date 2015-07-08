<?php
	class starpass extends payments {
		public function generateDepositForm($order_data = array()) {
			global $langArray, $currency, $config;

			$paymentsClass = new payments();
			$starpass = $paymentsClass->get('StarPass');

			$form  = '
                <div id="modal-starpass" class="modal hide fade modal-dix" tabindex="-1" role="dialog" aria-labelledby="modal-starpass" aria-hidden="true">
	                <div id="starpass_' . $starpass['token'] . '"></div>
	                <script type="text/javascript" src="http://script.starpass.fr/script.php?idd=' . $starpass['token'] . '&amp;verif_en_php=1&amp;datas="></script>
	                <noscript>' . $langArray['activate_browser_javascript'] . '<br /><a href="http://www.starpass.fr/">StarPass</a></noscript>
                </div>
			' . "\n";
			$form .= '<button id="starpass-deposit-button" class="btn btn-big-shadow" role="button" data-target="#modal-starpass" data-toggle="modal"><i class="hd-cart"></i> ' . $langArray['choose'] . '</button>'."\n";

			return $form;
		}
	}
?>