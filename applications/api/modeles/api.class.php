<?php
	class api extends base {
		public function isBuyedKey($download_key) {
			global $mysql;
			
			$mysql->query("
				SELECT id, member_id as buyer_id, owner_id as author_id, product_id, product_name, code_achat as code, paid_datetime as date
				FROM orders
				WHERE code_achat = '" . $download_key . "' && paid = 'true' && type='buy'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function memberApiKey($username) {
			global $mysql;
			
			$mysql->query("
				SELECT api_key
				FROM members
				WHERE username = '" . $username . "'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function memberBalance($authorUsername) {
			global $mysql;
			
			$mysql->query("
				SELECT username, total as balance
				FROM members
				WHERE username = '" . $authorUsername . "'
			");
			
			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

        public function checkCode($author_name, $apiKey, $purchaseKey) {
        	global $mysql;

            if (!isset($author_name) || empty($author_name)) {
                return false;
            }

			$membersClass = new members();
			$author = $membersClass->getByUsername($author_name);

            if (!isset($purchaseKey) || empty($apiKey)) {
                return false;
            }

            $checkPurchase = $this->isBuyedKey($purchaseKey);

			if ($checkPurchase && $author['member_id'] == $checkPurchase['author_id'] && $author['api_key'] == $apiKey) {
				$response = $checkPurchase;
			}
			else {
				$response = array(
					'error' => 'Invalid username or API Key.',
					'code' => 'not_authenticated'
				);
			}

            if (empty($response) || is_null($response)) {
                return false;
            }

            return $response;
        }

        public function getInformations($username, $apiKey) {
        	global $mysql;

            if (!isset($username) || empty($username)) {
                return false;
            }

            if (!isset($username) || empty($apiKey)) {
                return false;
            }

            $member = $this->memberBalance($username);
            $member_api_key = $this->memberApiKey($username);

			if ($member_api_key['api_key'] == $apiKey) {
				$response = $member;
			}
			else {
				$response = array(
					'error' => 'Invalid username or API Key.',
					'code' => 'not_authenticated'
				);
			}

            if (empty($response) || is_null($response)) {
                return false;
            }

            return $response;
        }
	}
?>