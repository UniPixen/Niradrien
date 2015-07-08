<?php
	class orders {
		public $row = '';
		public $whereQuery = '';
		public $membersWhere = '';

		public function getAll($start = 0, $limit = 0, $where = '', $order = 'paid_datetime DESC') {
			global $mysql, $language;
			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}
			
			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT *
				FROM orders
				$where
				ORDER BY $order
				$limitQuery
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM orders
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function add($price, $extended = 'false') {
			global $mysql, $product;

			function Generer_ID_Licence($caracteres_licence) {
				$lettres_licence = 'a,b,c,d,e,f,1,2,3,4,5,6,7,8,9,0'; 
				$array_licence = explode(',',$lettres_licence); 
				shuffle($array_licence); 
				$nouvelle_licence = implode($array_licence,''); 
				return substr($nouvelle_licence, 0, $caracteres_licence);
			}

			$generer_licence = Generer_ID_Licence(8) . '-' . Generer_ID_Licence(4) . '-' . Generer_ID_Licence(4) . '-' . Generer_ID_Licence(8);

			$mysql->query("
				INSERT INTO orders (
					member_id,
					owner_id,
					product_id,
					product_name,
					code_achat,
					price,
					datetime,
					extended,
					type
				)

				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . intval($product['member_id']) . "',
					'" . intval($product['id']) . "',
					'" . sql_quote($product['name']) . "',
					'" . sql_quote($generer_licence) . "',
					'" . sql_quote($price) . "',
					NOW(),
					'" . sql_quote($extended) . "',
					'buy'
				)
			");

			return $mysql->insert_id();
		}

		public function IsPay($order_id) {
			$row = $this->get($order_id);

			if(!is_array($row)) {
				return false;
			}

			return $row['paid'] == 'true' ? true : false;
		}

		public function orderIsPay($order_id) {
			global $mysql, $langArray, $config;
			
			$row = $this->get($order_id);
			
			if (!is_array($row)) {
				return 'error_paing'; // Erreur
			}

			// LOAD USERS SOLD
			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();
			$member = $membersClass->get($row['owner_id']);

			// Savoir le taux de reversement du membre
			require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
			$percentsClass = new percents();
			$percent = $percentsClass->getPercentRow($member);
			$percent = $percent['percent'];

			$receiveMoney = floatval($row['price']) * floatval($percent) / 100;
			
			$mysql->query("
				UPDATE orders
				SET paid = 'true',
					paid_datetime = NOW(),
					receive = '" . sql_quote($receiveMoney) . "'
				WHERE id = '" . intval($order_id) . "'
			");

			$mysql->query("
				UPDATE members
				SET earning = earning + '" . sql_quote($receiveMoney) . "',
					total = total + '" . sql_quote($receiveMoney)."',
					sold = sold + '" . floatval($row['price']) . "',
					sales = sales + 1
				WHERE member_id = '" . intval($row['owner_id']) . "'
				LIMIT 1
			");

			$you = $membersClass->get($row['member_id']);

			// CHECK REFERAL					
			if ($you['referal_id'] != '0') {
				$this->referalMoney($row, $you);
			}

			// UPDATE YOU BUY
			$mysql->query("
				UPDATE members
				SET buy = buy + 1
				WHERE member_id = '" . intval($row['member_id']) . "'
				LIMIT 1 
			");

			$mysql->query("
				UPDATE products
				SET sales = sales + 1,
					earning = earning + '" . sql_quote($row['price']) . "'
				WHERE id = '" . intval($row['product_id']) . "'
			");
			return true;
		}
		
		public function success() {
			global $mysql, $langArray, $config;
			require_once ROOT_PATH . '/system/classes/paypal.class.php';
			
			$paypalClass = new paypal();
			echo 1234;
			exit;

			if ($paypalClass->validate_ipn()) {
				if ($paypalClass->ipn_data['receiver_email'] == $config['paypal']['business'] && $paypalClass->ipn_data['txn_id'] != 0) {
					if ($paypalClass->ipn_data['payment_status'] == 'Completed') {
						$row = $this->get($paypalClass->ipn_data['custom']);
						if (!is_array($row)) {
							return 'error_paing'; // Erreur
						}

						if ($row['price'] > $paypalClass->ipn_data['mc_gross']) {
							return 'error_paing'; // Erreur
						}

						// LOAD USERS SOLD					
						require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
						$membersClass = new members();
						$member = $membersClass->get($row['owner_id']);

						// GET PERCENT
						require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
						$percentsClass = new percents();
						$percent = $percentsClass->getPercentRow($member);
						$percent = $percent['percent'];
						$receiveMoney = floatval($row['price']) * floatval($percent) / 100;

						$mysql->query("
							UPDATE orders
							SET paid = 'true',
								paid_datetime = NOW(),
								receive = '" . sql_quote($receiveMoney) . "'
							WHERE id = '" . intval($paypalClass->ipn_data['custom']) . "'
						");

						$mysql->query("
							UPDATE members
							SET earning = earning + '" . sql_quote($receiveMoney) . "',
								total = total + '" . sql_quote($receiveMoney) . "',
								sold = sold + '" . floatval($row['price']) . "',
								sales = sales + 1
							WHERE member_id = '" . intval($row['owner_id']) . "'
							LIMIT 1
						");

						$you = $membersClass->get($row['member_id']);

						// CHECK REFERAL					
						if ($you['referal_id'] != '0') {
							$this->referalMoney($row, $you);
						}

						// UPDATE YOU BUY
						$mysql->query("
							UPDATE members
							SET buy = buy + 1
							WHERE member_id = '" . intval($row['member_id']) . "'
							LIMIT 1 
						");

						// UPDATE ITEM
						$setQuery = '';

						$mysql->query("
							UPDATE products
							SET sales = sales + 1,
							$setQuery
							earning = earning + '" . sql_quote($row['price']) . "'
							WHERE id = '" . intval($row['id']) . "'
						");

						return true;
					}

					else {
						return 'error_paing'; // Erreur
					}
				}

				else {
					return 'error_paing'; // Erreur
				}
			}

			else {
				return 'error_paing'; // Erreur
			}
		}

		public function referalMoney($row, $you) {
			global $mysql;

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			require_once ROOT_PATH . '/applications/system/modeles/system.class.php';
			$systemClass = new system();

			$totals = $membersClass->getTotalReferals($you['member_id'], $you['referal_id']);
			$configure = $systemClass->getAllKeyValue();
			
			if ((int)$configure['referal_sum'] && ($totals+1) > (int)$configure['referal_sum']) {
				$mysql->query("
					UPDATE members
					SET referal_id = 0
					WHERE member_id = '" . intval($you['member_id']) . "'
					LIMIT 1 
					");
				return false;
			}
			
			$referalMoney = floatval($row['price']) * (int)$configure['referal_percent'] / 100;

			$mysql->query("
				UPDATE members
				SET earning = earning + '" . sql_quote($referalMoney) . "',
					total = total + '" . sql_quote($referalMoney) . "',
					referal_money = referal_money + '" . sql_quote($referalMoney) . "'
				WHERE member_id = '" . intval($you['referal_id']) . "'
				LIMIT 1
			");

			$mysql->query("
				INSERT INTO orders (
					member_id,
					owner_id,
					product_id,
					product_name,
					code_achat,
					price,
					datetime,
					receive,
					paid,
					paid_datetime,
					type
				)

				VALUES (
					'" . intval($row['member_id']) . "',
					'" . intval($row['owner_id']) . "',
					'" . intval($row['product_id']) . "',
					'" . sql_quote($generer_licence) . "',
					'" . sql_quote($row['price']) . "',
					NOW(),
					'" . sql_quote($referalMoney) . "',
					'true',
					NOW(),
					'referal'
				)
			");

			$mysql->query("
				INSERT INTO members_referals_count (
					member_id,
					referal_id,
					datetime
				)

				VALUES (
					'" . intval($you['member_id']) . "',
					'" . intval($you['referal_id']) . "',
					NOW()
				)
			");
		}

		public function buy($price, $extended = false) {
			global $mysql, $product;

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';

			$membersClass = new members();
			$you = $membersClass->get($_SESSION['member']['member_id']);
			$deposit = 0;
			$earning = 0;

			if ($you['deposit'] > $price) {
				$deposit = $price;
			}

			else {
				$deposit = $you['deposit'];
				$earning = floatval($price) - floatval($you['deposit']);
			}

			function Generer_ID_Licence($caracteres_licence) {
				$lettres_licence = 'a,b,c,d,e,f,1,2,3,4,5,6,7,8,9,0'; 
				$array_licence = explode(',', $lettres_licence); 
				shuffle($array_licence); 
				$nouvelle_licence = implode($array_licence,''); 
				
				return substr($nouvelle_licence, 0, $caracteres_licence);
			}

			$generer_licence = Generer_ID_Licence(8) . '-' . Generer_ID_Licence(4) . '-' . Generer_ID_Licence(4) . '-' . Generer_ID_Licence(8);

			// GET PRICE FROM USER

			$mysql->query("
				UPDATE members
				SET deposit = deposit - '" . floatval($deposit) . "',
					earning = earning - '" . floatval($earning) . "',
					total = total - '" . floatval($price) . "'
				WHERE member_id = '" . intval($you['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['deposit'] = floatval($_SESSION['member']['deposit']) - floatval($deposit);
			$_SESSION['member']['earning'] = floatval($_SESSION['member']['earning']) - floatval($earning);
			$_SESSION['member']['total'] = floatval($_SESSION['member']['total']) - floatval($price);

			// CHECK REFERAL
			if ($you['referal_id'] != '0') {
				$this->referalMoney(array(
					'price' => $price,
					'member_id' => $_SESSION['member']['member_id'],
					'owner_id' => $product['member_id'],
					'product_id' => $product['id'],
					'product_name' => $product['name']
				), $you);
			}

			// ADD PRICE TO OWNER USER
			$member = $membersClass->get($product['member_id']);
			
			require_once ROOT_PATH . '/applications/percents/modeles/percents.class.php';
			$percentsClass = new percents();
			$percent = $percentsClass->getPercentRow($member);
			$percent = $percent['percent'];

			$receiveMoney = floatval($price) * floatval($percent) / 100;

			$mysql->query("
				UPDATE members
				SET earning = earning + '" . floatval($receiveMoney) . "',
					total = total + '" . floatval($receiveMoney) . "',
					sold = sold + '" . floatval($price) . "',
					sales = sales + 1
				WHERE member_id = '" . intval($member['member_id']) . "'
				LIMIT 1
			");

			// ADD ORDER
			$mysql->query("
				INSERT INTO orders (
					member_id,
					owner_id,
					product_id,
					product_name,
					code_achat,
					price,
					datetime,
					receive,
					paid,
					paid_datetime,
					type
				)
				
				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . intval($product['member_id']) . "',
					'" . intval($product['id']) . "',
					'" . sql_quote($product['name']) . "',
					'" . sql_quote($generer_licence) . "',
					'" . sql_quote($price) . "',
					NOW(),
					'" . sql_quote($receiveMoney) . "',
					'true',
					NOW(),
					'buy'
				)
			");

			$mysql->query("
				UPDATE members
				SET buy = buy + 1
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1 
			");

			// UPDATE ITEM
			$setQuery = '';
			/*if ($extended) {
				$setQuery = " status = 'extended_buy', ";
			}*/

			$mysql->query("
				UPDATE products
				SET sales = sales + 1,
				$setQuery
				earning = earning + '" . sql_quote($price) . "'
				WHERE id = '" . intval($product['id']) . "'
			");

			return true;
		}

		public function isBuyed($id) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM orders
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' AND product_id = '" . intval($id) . "'  AND paid = 'true' AND type = 'buy'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$this->row = $mysql->fetch_array();

			return true;
		}

		public function isBuyedDownload($download_key) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM orders
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' && code_achat = '" . $download_key . "'  AND paid = 'true' AND type='buy'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$this->row = $mysql->fetch_array();

			return true;
		}

		public function isBuyedKey($download_key) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM orders
				WHERE owner_id = '" . intval($_SESSION['member']['member_id']) . "' && code_achat = '" . $download_key . "'  AND paid = 'true' AND type='buy'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$this->row = $mysql->fetch_array();

			return true;
		}

		public function buyedProductID($download_key) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM orders
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' && code_achat = '" . $download_key . "'  AND paid = 'true' AND type='buy'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$this->row = $mysql->fetch_array();

			// return true;
			return $this->row['product_id'];
		}

		public function getAllBuyed($where = '') {
			global $mysql, $meta;

			if ($where != '') {
				$where = ' WHERE ' . $where;
			}

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';

			while ($o = $mysql->fetch_array()) {
				if ($this->whereQuery != '') {
					$this->whereQuery .= ' OR ';
				}

				$this->whereQuery .= " id = '" . intval($o['product_id']) . "' ";

			}

			$mysql->query("
				SELECT p.id, p.member_id AS owner_id, p.name, p.thumbnail, p.categories, p.price AS product_price, p.status,
					   o.id AS order_id, o.member_id AS buyer_id, o.owner_id, o.product_id, o.product_name, o.code_achat, o.price AS paid_price, o.paid, o.paid_datetime, o.downloaded , o.extended, o.type
				FROM products p
				INNER JOIN orders o
				ON o.product_id = p.id
				$where
				ORDER BY o.paid_datetime DESC
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$this->membersWhere = '';

			while ($d = $mysql->fetch_array()) {
				$categories = explode('|', $d['categories']);
				unset($d['categories']);
				$d['categories'] = array();
				$row = 0;
				
				foreach($categories AS $cat) {
					$categories1 = explode(',', $cat);
					foreach($categories1 as $c) {
						$c = trim($c);
						if ($c != '') {
							$d['categories'][$row][$c] = $c;
						}
					}
				}

				$d = array_merge($d);

				$return[$d['order_id']] = $d;
				
				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}
				
				$this->membersWhere .= " o.owner_id = '" . intval($d['owner_id']) . "' ";
			}

			return $return;
		}

		public function getSales($memberID) {
			global $mysql, $meta;

			$mysql->query("
				SELECT p.id, p.member_id AS owner_id, p.name, p.thumbnail, p.categories, p.price AS product_price, p.status,
					   o.id AS order_id, o.member_id AS buyer_id, o.owner_id, o.product_id, o.product_name, o.code_achat, o.price AS paid_price, o.paid, o.paid_datetime, o.downloaded , o.extended, o.type
				FROM products p
				INNER JOIN orders o
				ON o.product_id = p.id
				WHERE o.owner_id = ". $memberID ." AND o.paid = 'true' AND o.type = 'buy'
				ORDER BY o.paid_datetime DESC
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			while ($d = $mysql->fetch_array()) {
				$categories = explode('|', $d['categories']);
				unset($d['categories']);
				$d['categories'] = array();
				$row = 0;
				
				foreach($categories AS $cat) {
					$categories1 = explode(',', $cat);
					foreach($categories1 as $c) {
						$c = trim($c);
						if ($c != '') {
							$d['categories'][$row][$c] = $c;
						}
					}
				}

				$d = array_merge($d);

				$return[$d['order_id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllMemberLicenses($memberID) {
			global $mysql, $meta;

			$mysql->query("
				SELECT p.id, p.member_id AS owner_id, p.name, p.thumbnail, p.categories, p.price AS product_price, p.status,
					   o.id AS order_id, o.member_id AS buyer_id, o.owner_id, o.product_id, o.product_name, o.code_achat, o.price AS paid_price, o.paid, o.paid_datetime, o.downloaded , o.extended, o.type
				FROM products p
				INNER JOIN orders o
				ON o.product_id = p.id
				WHERE o.member_id = '". $memberID ."' AND o.paid = 'true' AND o.type = 'buy'
				ORDER BY o.paid_datetime DESC
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			while ($d = $mysql->fetch_array()) {
				$categories = explode('|', $d['categories']);
				unset($d['categories']);
				$d['categories'] = array();
				$row = 0;
				
				foreach($categories AS $cat) {
					$categories1 = explode(',', $cat);
					foreach($categories1 as $c) {
						$c = trim($c);
						if ($c != '') {
							$d['categories'][$row][$c] = $c;
						}
					}
				}

				$d = array_merge($d);

				$return[$d['order_id']] = $d;
			}

			return $return;
		}

		public function getWeekStats() {
			global $mysql;

			$buff = 6 - date('N');	
			$lastWeekDay = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d') - $buff - date('N')), date('Y')));
			$firstWeekDay = date('Y-m-d', mktime(0, 0, 0, date('m'), (date('d') + $buff + 2), date('Y')));
			//		echo $lastWeekDay.' '.$firstWeekDay;

			$mysql->query("
				SELECT *
				FROM orders
				WHERE owner_id = '" . intval($_SESSION['member']['member_id']) . "' AND paid = 'true' AND datetime > '" . $lastWeekDay . "' AND datetime < '" . $firstWeekDay . "'
			");

			$weekStats = array('earning' => 0, 'sold' => 0);

			if ($mysql->num_rows() == 0) {
				return $weekStats;
			}

			while($d = $mysql->fetch_array()) {
				$weekStats['sold']++;
				$weekStats['earning'] += $d['receive'];
			}

			return $weekStats;
		}

		public function getHistory($memberID, $month, $year) {
			global $mysql;

			$lastMonth = date('Y-m-d 23:59:59', mktime(0, 0, 0, ($month - 1), date('t', mktime(0, 0, 0, ($month - 1), 1, $year)), $year));
			$nextMonth = date('Y-m-d 00:00:00', mktime(0, 0, 0, ($month + 1), 1, $year));	

			$mysql->query("
			(
				SELECT member_id, owner_id, price, receive, paid_datetime as datetime, product_name, type as referal, CONCAT('order') as type
				FROM orders
				WHERE (owner_id = '" . intval($memberID) . "' OR member_id = '" . intval($memberID) . "') AND paid = 'true' AND paid_datetime > '" . $lastMonth . "' AND paid_datetime < '" . $nextMonth . "'
			)

			UNION
			(
				SELECT member_id, CONCAT('') as owner_id, deposit as price, CONCAT('') as receive, datetime, CONCAT('') as product_name, CONCAT('') as referal, CONCAT('deposit') as type
				FROM deposit
				WHERE member_id = '" . intval($memberID) . "' AND paid = 'true' AND datetime > '" . $lastMonth . "' AND datetime < '" . $nextMonth . "'
			)

			UNION
			(
				SELECT member_id, CONCAT('') as owner_id, amount as price, CONCAT('') as receive, datetime, CONCAT('') as product_name, CONCAT('') as referal, CONCAT('withdraw') as type
				FROM withdraw
				WHERE member_id = '" . intval($memberID) . "' AND paid = 'true' AND datetime > '" . $lastMonth . "' AND datetime < '" . $nextMonth . "'
			)

			ORDER BY datetime DESC
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

		public function getTopSellers($start = 0, $limit = 0, $where = '') {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT *, COUNT(product_id) AS sales 
				FROM orders
				WHERE type = 'buy' AND paid = 'true' $where
				GROUP BY product_id
				ORDER BY sales DESC
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';

			while ($d = $mysql->fetch_array()) {
				$return[$d['product_id']] = $d;

				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " id = '" . intval($d['product_id']) . "' ";
			}

			$mysql->query("
				SELECT *
				FROM products
				WHERE $whereQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$this->membersWhere = '';

			while ($d = $mysql->fetch_array()) {
				$d['sales'] = $return[$d['id']]['sales'];
				$categories = explode('|', $d['categories']);
				unset($d['categories']);

				$d['categories'] = array();

				$row = 0;

				foreach($categories AS $cat) {
					$categories1 = explode(',', $cat);

					foreach($categories1 as $c) {
						$c = trim($c);

						if ($c != '') {
							$d['categories'][$row][$c] = $c;
						}
					}

					$row++;
				}

				$return[$d['id']] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}

				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			return $return;
		}

		public function getTopAuthors($start = 0, $limit = 0, $where = '') {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			$mysql->query("
				SELECT *, COUNT(owner_id) AS sales 
				FROM orders
				WHERE type = 'buy' AND paid = 'true' $where
				GROUP BY owner_id
				ORDER BY sales DESC
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			$whereQuery = '';

			while ($d = $mysql->fetch_array()) {
				$return[$d['owner_id']] = $d;
				if($whereQuery != '') {
					$whereQuery .= ' OR ';
				}

				$whereQuery .= " member_id = '" . intval($d['owner_id']) . "' ";
			}

			$mysql->query("
				SELECT *
				FROM members
				WHERE $whereQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			while($d = $mysql->fetch_array()) {
				$d['sales'] = $return[$d['member_id']]['sales'];
				$return[$d['member_id']] = $d;
			}

			return $return;
		}

		public function isProductBuyed($productID, $membersWhere) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM orders
				WHERE product_id = '" . intval($productID) . "' AND type = 'buy' AND paid = 'true' AND ($membersWhere)			
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array()) {
				$return[$d['member_id']] = $d;
			}

			return $return;
		}

		public function getSalesStatus($whereQuery='', $type='buy') {
			global $mysql;

			$mysql->query("
				SELECT COUNT(id) as num, SUM(price) as total, SUM(receive) AS receive 
				FROM orders 
				WHERE type = '" . sql_quote($type) . "' AND paid = 'true' $whereQuery 
				GROUP BY type
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function getSalesStatusByDay($whereQuery = '', $type = 'buy') {
			global $mysql;

			$mysql->query("
				SELECT COUNT(id) as num, SUM(price) as total, SUM(receive) AS receive , DATE(datetime) AS date
				FROM orders 
				WHERE type = '" . sql_quote($type) . "' AND paid = 'true' $whereQuery 
				GROUP BY DATE(datetime)
				ORDER BY DATE(datetime) ASC
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$data = array();

			while ($d = $mysql->fetch_array()) {
				$data[$d['date']] = $d;
			}

			return $data;
		}
	}
?>