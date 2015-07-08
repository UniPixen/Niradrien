<?php 
	class deposit {
		public $foundRows = 0;
		public $membersWhere = '';	

		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start,$limit ";
			}

			if ($where != '') {
				$where = " WHERE ".$where;
			}

			$mysql->query("
				SELECT *
				FROM deposit
				$where
				ORDER BY datetime DESC
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[] = $d;
			}

			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM deposit
				WHERE id = '" . intval($id) . "'
			");

			return $mysql->fetch_array();
		}

		public function add() {
			global $mysql;
			if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
				return false;
			}

			$mysql->query("
				INSERT INTO deposit (
					member_id,
					deposit,
					datetime
				)

				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['amount']) . "',
					NOW()
				)
			");

			return $mysql->insert_id();
		}

		

		public function depositIsPay($deposit_id) {
			global $mysql, $langArray, $config;
			$row = $this->get($deposit_id);
			
			if ($row) {
				if ($row['paid'] == 'true') {
					return;
				}

				$mysql->query("
					UPDATE members
					SET deposit = deposit + '" . sql_quote($row['deposit']) . "',
						total = total + '" . sql_quote($row['deposit']) . "'
					WHERE member_id = '" . intval($row['member_id']) . "'
					LIMIT 1
				");

				$mysql->query("
					UPDATE deposit
					SET paid = 'true'
					WHERE id = '" . intval($deposit_id) . "'
				");

				if (isset($_SESSION['member'])) {
					$_SESSION['member']['deposit'] = floatval($_SESSION['member']['deposit']) + floatval($row['deposit']);
					$_SESSION['member']['total'] = floatval($_SESSION['member']['total']) + floatval($row['deposit']);
				}

				require_once ROOT_PATH . '/system/classes/history.class.php';
				$historyClass = new history();
				$historyClass->add($row['deposit'], $deposit_id, $row['member_id']);

				// CHECK REFERAL
				require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
				$membersClass = new members();

				$member = $membersClass->get($row['member_id']);
				if ($member['referal_id'] != '0') {
					$this->referalMoney($row, $member);
				}
			}
		}

		public function success() {
			global $mysql, $langArray, $config;
			require_once ROOT_PATH . '/system/classes/paypal.class.php';
			$paypalClass = new paypal();
			
			if ($paypalClass->validate_ipn()) {
				if ($paypalClass->ipn_data['receiver_email'] == $config['paypal']['business'] && $paypalClass->ipn_data['txn_id'] != 0) {
					if ($paypalClass->ipn_data['payment_status'] == 'Completed' || $paypalClass->ipn_data['payment_status'] == 'Pending') {
						$row = $this->get($paypalClass->ipn_data['custom']);
						if (!is_array($row)) {
							return 'error_deposit'; // Erreur
						}

						if ($row['deposit'] != $paypalClass->ipn_data['mc_gross']) {

							return 'error_deposit'; // Erreur

						}

						$mysql->query("
							UPDATE members
							SET deposit = deposit + '" . sql_quote($paypalClass->ipn_data['mc_gross']) . "',
								total = total + '" . sql_quote($paypalClass->ipn_data['mc_gross']) . "'
							WHERE member_id = '" . intval($row['member_id']) . "'
							LIMIT 1
						");

						$mysql->query("
							UPDATE deposit
							SET paid = 'true'								
							WHERE id = '" . intval($paypalClass->ipn_data['custom']) . "'
						");

						$_SESSION['member']['deposit'] = floatval($_SESSION['member']['deposit']) + floatval($paypalClass->ipn_data['mc_gross']);
						$_SESSION['member']['total'] = floatval($_SESSION['member']['total']) + floatval($paypalClass->ipn_data['mc_gross']);
						
						require_once ROOT_PATH . '/system/classes/history.class.php';
						
						$historyClass = new history();
						$historyClass->add($paypalClass->ipn_data['mc_gross'], $paypalClass->ipn_data['txn_id']);

						// CHECK REFERAL
						require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
						$membersClass = new members();
						$member = $membersClass->get($row['member_id']);
						
						if ($member['referal_id'] != '0') {
							$this->referalMoney($row, $member);
						}

						return true;
					}

					else {
						return 'error_order_payment';
					}
				}

				else {
					return 'error_order_payment';
				}
			}

			else {
				return 'error_order_payment';
			}
		}

		public function referalMoney($row, $member) {
			global $mysql;
			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			require_once ROOT_PATH . '/applications/system/modeles/system.class.php';
			$systemClass = new system();

			$totals = $membersClass->getTotalReferals($member['member_id'], $member['referal_id']);
			$configure = $systemClass->getAllKeyValue();

			if ((int)$configure['referal_sum'] && ($totals + 1) > (int)$configure['referal_sum']) {
				$mysql->query("
					UPDATE members
					SET referal_id = 0
					WHERE member_id = '" . intval($member['member_id']) . "'
					LIMIT 1 
				");
				return false;
			}
			
			$referalMoney = floatval($row['deposit']) * (int)$configure['referal_percent'] / 100;
			$mysql->query("
				UPDATE members
				SET earning = earning + '" . sql_quote($referalMoney) . "',
					total = total + '" . sql_quote($referalMoney) . "',
					referal_money = referal_money + '" . sql_quote($referalMoney) . "'									
				WHERE member_id = '" . intval($member['referal_id']) . "'
				LIMIT 1
			");

			$mysql->query("
				INSERT INTO orders (
					member_id,
					owner_id,
					product_id,
					product_name,
					price,
					datetime,
					receive,
					paid,
					paid_datetime,
					type
				)

				VALUES (
					'" . intval($member['member_id']) . "',
					'" . intval($member['referal_id']) . "',
					'0',
					'deposit',
					'" . sql_quote($row['deposit']) . "',
					NOW(),
					'" . sql_quote($referalMoney) . "',
					'true',
					NOW(),
					'referal'
				)
			");
		}

		public function addWithdraw() {
			global $mysql, $langArray, $member;

			if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
				$error['amount'] = $langArray['wrong_amount'];
			}
			
			else {
				if (isset($_POST['service']) && $_POST['service'] == 'swift') {
					if ($_POST['amount'] < 500) {
						$error['amount'] = $langArray['wrong_amount2'];
					}
					$maxAmount = $member['earning'] - 35;
				}
				else {
					if ($_POST['amount'] < 50) {
						$error['amount'] = $langArray['wrong_amount2'];
					}
					$maxAmount = $member['earning'];
				}

				if ($_POST['amount'] > $maxAmount) {
					$error['amount'] = $langArray['wrong_amount2'];
				}
			}

			if (!isset($_POST['service'])) {
				$error['service'] = $langArray['error_service'];
			}

			else {
				if ($_POST['service'] == 'swift' && (!isset($_POST['instructions_from_author']) || trim($_POST['instructions_from_author']) == '')) {
					$error['service2'] = $langArray['error_details'];
				}

				elseif (!isset($_POST['payment_email_address']) || !isset($_POST['payment_email_address_confirmation']) || trim($_POST['payment_email_address']) == '' || $_POST['payment_email_address'] !== $_POST['payment_email_address_confirmation']) {
					$error['service2'] = $langArray['error_payment_email_address'];				
				}
			}

			if (isset($_POST['taxable_french_resident']) && $_POST['hobbyist'] == 'false' && trim($_POST['abn']) == '' && trim($_POST['acn']) == '') {
				$error['french'] = $langArray['error_french_resident'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['taxable_french_resident'])) {
				$_POST['taxable_french_resident'] = 'false';
			}

			else {
				if ($_POST['hobbyist'] == 'true') {
					$_POST['taxable_french_resident'] = 'iam';
				}

				elseif ($_POST['hobbyist'] == 'false') {			
					$_POST['taxable_french_resident'] = 'iamnot';
				}
			}
			
			$payment_email = $_POST['payment_email_address'];

			$mysql->query("
				INSERT INTO withdraw (
					member_id,
					amount,
					method,
					payment_email,
					french,
					datetime
				)

				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['amount']) . "',
					'" . sql_quote($_POST['service']) . "',
					'" . sql_quote($payment_email) . "',
					'" . sql_quote($_POST['taxable_french_resident']) . "',
					NOW()
				)
			");

			return true;
		}

		public function getWithdraws($start = 0, $limit = 0) {
			global $mysql;

			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = " LIMIT $start,$limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM withdraw
				ORDER BY datetime DESC
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}

				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}
		
		public function getWithdraw($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM withdraw
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function deleteWithdraw($id) {
			global $mysql;

			$row = $this->getWithdraw($id);

			if (!is_array($row) || $row['paid'] == 'true') {
				return true;
			}

			$mysql->query("
				DELETE FROM withdraw
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function payoutWithdraw() {
			global $mysql, $langArray, $member, $data;

			if (!isset($_POST['payout']) || !is_numeric($_POST['payout']) || $_POST['payout'] < 1) {
				return $langArray['error_set_valid_sum'];
			}

			if ($_POST['payout'] > $member['earning']) {
				return $langArray['error_not_enought_money_earning'];
			}

			$mysql->query("
				UPDATE members
				SET earning = earning - '" . floatval($_POST['payout']) . "',
					total = total - '" . floatval($_POST['payout']) . "'
				WHERE member_id = '" . intval($member['member_id']) . "'
				LIMIT 1
			");

			$mysql->query("
				UPDATE withdraw
				SET paid = 'true',
					paid_datetime = NOW()
				WHERE id = '" . intval($data['id']) . "'
				LIMIT 1
			");

			return true;
		}
		
		public function getWithdrawCount($whereQuery) {
			global $mysql;

			if ($whereQuery != '') {
				$whereQuery = " WHERE ".$whereQuery;
			}

			$mysql->query("
				SELECT COUNT(id) AS num, SUM(amount) AS total
				FROM withdraw
				$whereQuery
			");

			return $mysql->fetch_array();
		}
	}
?>