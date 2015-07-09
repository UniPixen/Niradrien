<?php
	class members extends base {
		public $uploadFileDirectory;
		public $foundRows = 0;
		public $avatarError = false;
		public $homeimageError = false;
		public $membersWhere = '';

		function __construct() {
			global $config;

			$this->photoSizes = $config['avatar_photo_sizes'];
			$this->uploadFileDirectory = 'members/';
		}

		public function getAll($start = 0, $limit = 0, $where = '', $order = 'username ASC') {
			global $mysql;
			
			$limitQuery = '';
			
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}
			
			if ($where != '') {
				$where = "WHERE " . $where;
			}

			$a = $mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *,
				(SELECT COUNT(follow_id) FROM members_followers WHERE follow_id = members . member_id) AS followers,
				(SELECT COUNT(id) FROM products WHERE member_id = members . member_id && status = 'active') as products
				FROM members
				$where
				ORDER BY $order
				$limitQuery
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while ($d = $mysql->fetch_array($a)) {
				$return[$d['member_id']] = $d;

				if (!empty($d['groups'])) {
					$groups = json_decode($d['groups'], true);
					unset($d['groups']);

					if (is_array($groups) && !empty($groups)) {
						$groupsWhere = '';
						
						foreach($groups as $k => $v) {
							$d['groups'][$k] = $v;

							if (!empty($groupsWhere)) {
								$groupsWhere .= " OR ";
							}

							$groupsWhere .= " ug_id = '" . intval($k) . "' ";
						}

						$b = $mysql->query("
							SELECT *
							FROM members_groups
							WHERE $groupsWhere
						", __FUNCTION__ );

						if ($mysql->num_rows() > 0) {
							$return[$d['member_id']]['is_admin'] = true;

							while ($b = $mysql->fetch_array()) {
								$applications = json_decode($b['rights'], true);
								
								foreach ($applications as $k => $v) {
									if (!isset($return[$d['member_id']]['applications'][$k])) {
										$return[$d['member_id']]['applications'][$k] = true;
									}
								}
							}

							$return[$d['member_id']]['access'] = $return[$d['member_id']]['applications'];
						}

						else {
							$return[$d['member_id']]['applications'] = '';
						}
					}

					else {
						$return[$d['member_id']]['groups'] = '';
					}
				}

				$b = $mysql->query("
					SELECT *
					FROM countries
					WHERE id = " . intval($d['country_id']) . "
				");

				$return[$d['member_id']]['country'] = $mysql->fetch_array($b);

				$b = $mysql->query("
					SELECT *
					FROM members_status
					WHERE member_id = " . intval($d['member_id']) . "
				");

				if ($mysql->num_rows($b) > 0) {
					while ($d = $mysql->fetch_array($b)) {
						$return[$d['member_id']]['statuses'][$d['status']] = $d;
					}
				}
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *,
				(SELECT COUNT(follow_id) FROM members_followers WHERE follow_id = members . member_id) AS followers,
				(SELECT COUNT(id) FROM products WHERE member_id = members . member_id && status = 'active') as products
				FROM members
				$where
				ORDER BY $order
				$limitQuery
			", __FUNCTION__ );

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function get($id) {
			global $mysql, $langArray;

			$return = $mysql->getRow("
				SELECT *
				FROM members
				WHERE member_id = '" . intval($id) . "'
			" );

			if (is_array($langArray['moneyArr'])) {
				foreach ($langArray['moneyArr'] as $k => $v) {
					$return['moneyText'] = $v;
					if ($return['buy'] <= $k) {
						break;
					}
				}
			}

			if (is_array($langArray['earningArr'])) {
				foreach($langArray['earningArr'] as $k => $v) {
					$return['earningText'] = $v;
					if ($return['sold'] <= $k) {
						break;
					}
				}
			}

			if (!empty($return['license'])) {
				$buff = json_decode($return['license'], true);
				unset($return['license']);
				$return['license'] = $buff;
				unset($buff);
			}

			if (!empty($return['social'])) {
				$buff = json_decode($return['social'], true);
				unset($return['social']);
				$return['social'] = $buff;
				unset($buff);
			}

			if (!empty($return['groups'])) {
				$groups = json_decode($return['groups'], true);
				unset($return['groups']);
				
				if (is_array($groups) && !empty($groups)) {
					$groupsWhere = '';

					foreach($groups as $k => $v) {
						$return['groups'][$k] = $v;

						if (!empty($groupsWhere)) {
							$groupsWhere .= " OR ";
						}

						$groupsWhere .= " ug_id = '" . intval($k) . "' ";
					}

					$mysql->query("
						SELECT *
						FROM members_groups
						WHERE $groupsWhere
					", __FUNCTION__ );

					if ($mysql->num_rows() > 0) {
						$return['is_admin'] = true;

						while ($d = $mysql->fetch_array()) {
							$applications = json_decode($d['rights'], true);
							
							foreach ($applications as $k => $v) {
								if (!isset($return['applications'][$k])) {
									$return['applications'][$k] = true;
								}
							}
						}

						$return['access'] = $return['applications'];
					}

					else {
						$return['applications'] = '';
					}
				}

				else {
					$return['groups'] = '';
				}
			}

			$mysql->query("
				SELECT *
				FROM countries
				WHERE id = '" . intval($return['country_id']) . "'
			");

			$return['country'] = $mysql->fetch_array();

			$mysql->query("
				SELECT *
				FROM members_status
				WHERE member_id = '" . intval($return['member_id']) . "'
			");

			if ($mysql->num_rows() > 0) {
				while($d = $mysql->fetch_array()) {
					$return['statuses'][$d['status']] = $d;
				}
			}

			return $return;
		}

		public function getByUsername($username) {
			global $mysql;

			$return = $mysql->getRow("
				SELECT *
				FROM members
				WHERE username = '" . sql_quote($username) . "'
			" );

			if (!is_array($return)) {
				return false;
			}

			$buff = json_decode($return['license'], true);
			unset($return['license']);
			
			$return['license'] = $buff;
			unset($buff);
			
			$buff = json_decode($return['social'], true);
			unset($return['social']);
			
			$return['social'] = $buff;
			unset($buff);

			$groups = json_decode($return['groups'], true);
			unset($return['groups']);
			
			if (is_array($groups) && !empty($groups)) {
				$groupsWhere = '';

				foreach($groups as $k => $v) {
					$return['groups'][$k] = $v;

					if (!empty($groupsWhere)) {
						$groupsWhere .= " OR ";
					}

					$groupsWhere .= " ug_id = '" . intval($k) . "' ";
				}

				$mysql->query("
					SELECT *
					FROM members_groups
					WHERE $groupsWhere
				", __FUNCTION__ );

				if ($mysql->num_rows() > 0) {
					$return['is_admin'] = true;

					while($d = $mysql->fetch_array()) {
						$applications = json_decode($d['rights'], true);
						
						foreach($applications as $k => $v) {
							if (!isset($return['applications'][$k])) {
								$return['applications'][$k] = true;
							}
						}
					}
				}

				else {
					$return['applications'] = '';
				}
			}

			else {
				$return['groups'] = '';
			}

			// CHARGER LE PAYS DU MEMBRE
			if ($return['country_id'] != '0') {
				require_once ROOT_PATH . '/applications/countries/modeles/countries.class.php';
				$countriesClass = new countries();

				$return['country'] = $countriesClass->get($return['country_id']);
			}

			// CHARGER LE STATUS DU MEMBRE
			$mysql->query("
				SELECT *
				FROM members_status
				WHERE member_id = '" . intval($return['member_id']) . "'
			");

			if ($mysql->num_rows() > 0) {
				while ($d = $mysql->fetch_array()) {
					$return['statuses'][$d['status']] = $d;
				}
			}

			return $return;
		}

		public function isExistUsername($username) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM members
				WHERE username = '" . sql_quote($username) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		private function isExistEmail($email, $without = '') {
			global $mysql;

			$whereQuery = '';
			if (!empty($without)) {
				$whereQuery = " AND email <> '" . sql_quote($without) . "' ";
			}

			$mysql->query("
				SELECT *
				FROM members
				WHERE email = '" . sql_quote($email) . "' $whereQuery
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		// Ajouter un membre (inscription)
		public function add() {
			global $mysql, $langArray, $config, $meta;

			if (!isset($_POST['firstname']) || trim($_POST['firstname']) == '') {
				$error['firstname'] = $langArray['error_fill_firstname'];
			}

			if (!isset($_POST['lastname']) || trim($_POST['lastname']) == '') {
				$error['lastname'] = $langArray['error_fill_lastname'];
			}

			if (!isset($_POST['email']) || trim($_POST['email']) == '') {
				$error['email'] = $langArray['error_fill_email'];
			}

			elseif (!check_email($_POST['email'])) {
				$error['email'] = $langArray['error_not_valid_email'];
			}

			elseif ($this->isExistEmail($_POST['email'])) {
				$error['email'] = $langArray['error_exist_email'];
			}

			if (!isset($_POST['email_confirm']) || trim($_POST['email_confirm']) == '') {
				$error['email_confirm'] = $langArray['error_fill_email_confirm'];
			}

			if (isset($_POST['email']) && isset($_POST['email_confirm']) && $_POST['email'] !== $_POST['email_confirm']) {
				$error['email_confirm'] = $langArray['error_emails_not_match'];
			}

			if (!isset($_POST['username']) || trim($_POST['username']) == '') {
				$error['username'] = $langArray['error_not_set_username'];
			}

			elseif (!preg_match('/^[a-zA-Z0-9_]+$/i', $_POST['username'])) {
				$error['username'] = $langArray['error_not_valid_username'];
			}

			elseif ($this->isExistUsername($_POST['username'])) {
				$error['username'] = $langArray['error_exist_username'];
			}

			if (!isset($_POST['password']) || trim($_POST['password']) == '') {
				$error['password'] = $langArray['error_fill_password'];
			}

			if (!isset($_POST['password_confirm']) || trim($_POST['password_confirm']) == '') {
				$error['password_confirm'] = $langArray['error_fill_password_confirm'];
			}

			elseif (isset($_POST['password']) && isset($_POST['password_confirm']) && $_POST['password'] !== $_POST['password_confirm']) {
				$error['password_confirm'] = $langArray['error_password_not_match'];
			}

			require_once ROOT_PATH . '/system/classes/recaptcha.php';
			$recaptchaClass = new ReCaptcha($meta['recaptcha_private_key']);

	  	    if ($recaptchaClass->checkCode($_POST['g-recaptcha-response']) === false) {
		        $error['captcha'] = $langArray['error_wrong_captcha'];
	  	    }

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['status'])) {
				$_POST['status'] = 'waiting';
			}

			$groups = array();
			
			if (isset($_POST['groups']) && is_array($_POST['groups'])) {
				foreach($_POST['groups'] as $k => $v) {
					$groups[$k] = $v;
				}
			}

			$activationKey = md5(rand(0, 10000) . date('HisdmY') . rand(0, 10000));
			$apiKey = substr(md5(rand(0, 10000) . date('HisdmY') . rand(0, 10000)), 0, 32);
			$referalID = 0;
			
			if (isset($_SESSION['temp']['referal'])) {
				if ($this->isExistUsername($_SESSION['temp']['referal'])) {
					$referalID = $mysql->fetch_array();
					$referalID = $referalID['member_id'];
				}

				unset($_SESSION['temp']['referal']);
			}

			$mysql->query("
				INSERT INTO members (
					username,
					password,
					email,
					firstname,
					lastname,
					exclusive_author,
					register_datetime,
					status,
					groups,
					activate_key,
					api_key,
					referal_id
				)
				VALUES (
					'" . sql_quote($_POST['username']) . "',
					'" . md5(md5($_POST['password'])) . "',
					'" . sql_quote($_POST['email']) . "',
					'" . sql_quote($_POST['firstname']) . "',
					'" . sql_quote($_POST['lastname']) . "',
					'false',
					NOW(),
					'" . sql_quote($_POST['status']) . "',
					'" . json_encode($groups) . "',
					'" . sql_quote($activationKey) . "',
					'" . sql_quote($apiKey) . "',
					'" . intval($referalID) . "'
				)
			", __FUNCTION__ );

			if ($referalID != 0) {
				$mysql->query("
					UPDATE members
					SET referals = referals + 1
					WHERE member_id = '" . intval($referalID) . "'
					LIMIT 1
				");
			}

			// AJOUTER À LA NEWSLETTER
			if (isset($_POST['subscribe'])) {
				require_once ROOT_PATH . '/applications/newsletter/modeles/newsletter.class.php';
				$newsletterClass = new newsletter();
				$newsletterClass->addNewsletterEmail($_POST['email']);
			}

			// ENVOYER LE LIEN D'ACTIVATION
			require_once SYSTEM_PATH . '/classes/email.class.php';
			$emailClass = new email();

			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = $langArray['email_activate_subject'];
			
			$emailClass->message = emailTemplate(
				$langArray['email_activate_subject'],
				langMessageReplace(
					$langArray['email_activate_text'], array(
						'SITE_TITLE' => $meta['website_title']
					)
				),
				'http://' . $config['domain'] . '/login/?command=activate&member=' . $_POST['username'] . '&key=' . $activationKey,
				$langArray['confirm'],
				$langArray['email_no_spam']
			);

			$emailClass->to($_POST['email']);
			$emailClass->send();

			return true;
		}

		// ÉDITER LE MEMBRE
		public function edit($id, $editFromAdmin = true) {
			global $mysql, $config, $langArray;

			$setQuery = '';

			if (isset($_POST['status'])) {
				$setQuery .= " status = '" . sql_quote($_POST['status']) . "' ";
			}

			if ($editFromAdmin) {
				$groups = array();
				
				if (isset($_POST['groups']) && is_array($_POST['groups'])) {
					foreach($_POST['groups'] as $k => $v) {
						$groups[$k] = $v;
					}
				}

				if ($setQuery != '') {
					$setQuery .= ',';
				}

				$setQuery .= " groups = '" . json_encode($groups) . "' ";

				if (isset($_POST['author'])) {
					$setQuery .= " , author = 'true' ";
				}

				else {
					$setQuery .= " , author = 'false' ";
				}

				if (isset($_POST['featured_author'])) {
					$setQuery .= " , featured_author = 'true' ";
				}

				else {
					$setQuery .= " , featured_author = 'false' ";
				}

				if (isset($_POST['super_elite_author'])) {
					$setQuery .= " , super_elite_author = 'true' ";
				}

				else {
					$setQuery .= " , super_elite_author = 'false' ";
				}

				if (isset($_POST['elite_author'])) {
					$setQuery .= " , elite_author = 'true' ";
				}
				else {
					$setQuery .= " , elite_author = 'false' ";
				}

				if (isset($_POST['badges'])) {
					$setQuery .= " , badges = '" . implode(',', $_POST['badges']) . "' ";
				}

				else {
					$setQuery .= " , badges = '' ";
				}

				if (isset($_POST['password']) && trim($_POST['password']) != '') {
					$setQuery .= " , password = '" . md5(md5($_POST['password'])) . "' ";
				}

				if (isset($_POST['commission_percent'])) {
					$setQuery .= " , commission_percent = '" . (int)$_POST['commission_percent'] . "' ";
				}
			}

			if ($setQuery != '') {
				$mysql->query("
					UPDATE members
					SET $setQuery
					WHERE member_id = " . intval($id) . "
					LIMIT 1
				", __FUNCTION__ );
			}

			return true;
		}

		// SUPPRIMER LE MEMBRE ET TOUT CE QUI EST EN RAPPORT AVEC LUI
		public function delete($id) {
			global $mysql;

			recursive_rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/', true);

			$mysql->query("
				DELETE FROM members
				WHERE member_id = '" . intval($id) . "'
				LIMIT 1
			", __FUNCTION__ );

			$mysql->query("
				DELETE FROM members_followers
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_followers
				WHERE follow_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_messages
				WHERE from_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_messages
				WHERE to_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_referals_count
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_referals_count
				WHERE referal_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM members_status
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM team
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM deposit
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM favorites
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM forum_threads
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM history
				WHERE member_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM withdraw
				WHERE member_id = '" . intval($id) . "'
			");

			return true;
		}

		private function deleteAvatar($id) {
			global $mysql, $config;

			$member = $this->get($id);
			
			if (!is_array($member)) {
				return false;
			}

			deleteFile (DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/' . $member ['avatar']);

			$mysql->query("
				UPDATE members
				SET avatar = NULL
				WHERE member_id = '" . intval($id) . "'
				LIMIT 1
			", __FUNCTION__ );

			return true;
		}

		private function deleteHomeimage($id) {
			global $mysql, $config;

			$member = $this->get($id);
			
			if (!is_array($member)) {
				return false;
			}

			deleteFile (DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/' . $member ['homeimage']);

			$mysql->query("
				UPDATE members
				SET homeimage = NULL
				WHERE member_id = '" . intval($id) . "'
				LIMIT 1
			", __FUNCTION__ );

			return true;
		}

		// CONNEXION
		public function login($admin = false) {
			global $mysql, $config;

			if (!isset($_POST['username']) || !isset($_POST['password'])) {
				return 'error_invalid_username_or_password';
			}

			$mysql->query("
				SELECT *
				FROM members
				WHERE username = '" . sql_quote($_POST['username']) . "' AND password = '" . md5(md5($_POST['password'])) . "' AND status = 'activate'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return 'error_invalid_username_or_password';
			}

			$row = $mysql->fetch_array();
			$member = $this->get($row['member_id']);

			if ($member['last_login_datetime'] == '' || $member['last_login_datetime'] == '0000-00-00 00:00:00') {
				$member['first_login'] = 'yes';
			}

			if ($admin && ($member['groups'] == false || count($member['groups']) < 1)) {
				return 'error_invalid_username_or_password';
			}

			$verKey = '';
			
			if (isset($_POST['rememberme'])) {
				$verKey = md5(rand(0,9999999) . time() . $member['member_id']);

				setcookie('member_id', $member['member_id'], time() + 2592000, '/', '.' . $config['domain']);
	      		setcookie('verifyKey', $verKey, time() + 2592000, '/', '.' . $config['domain']);
			}

			$mysql->query("
				UPDATE members
				SET last_login_datetime = NOW(),
					ip_address = '" . sql_quote($_SERVER['REMOTE_ADDR']) . "',
					remember_key = '" . sql_quote($verKey) . "'
				WHERE member_id = '" . intval($member['member_id']) . "'
				LIMIT 1
			", __FUNCTION__ );

			$_SESSION['member'] = $member;

			return true;
		}

		public function isValidVerifyKey($member_id, $key) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM members
				WHERE member_id = '" . intval($member_id) . "' AND remember_key = '" . sql_quote($key) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function isValidActivateKey($username, $key) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM members
				WHERE username = '" . sql_quote($username) . "' AND activate_key = '" . sql_quote($key) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function activateMember($username, $key) {
			global $mysql, $langArray;

			if (!$this->isValidActivateKey($username, $key)) {
				$error['valid'] = $langArray['error_not_valid_activate_key'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE members
				SET status = 'activate',
					activate_key = NULL
				WHERE username = '" . sql_quote($username) . "' AND activate_key = '" . sql_quote($key) . "'
				LIMIT 1
			");

			$_SESSION['member'] = $this->getByUsername($username);

			return true;
		}

		// MOT DE PASSE OUBLIÉ
		public function lostPassword() {
			global $mysql, $langArray, $config;

			$mysql->query("
				SELECT *
				FROM members
				WHERE username = '" . sql_quote($_POST['username']) . "' AND email = '" . sql_quote($_POST['email']) . "'
			", __FUNCTION__ );

			// SI LE NOM D'UTILISATEUR OU MOT DE PASSE N'EXISTENT PAS OU NE SONT PAS CORRECT, ON AFFICHE L'ERREUR
			if ($mysql->num_rows() == 0) {
				return 'error_invalid_username_email';
			}

			// ON RÉCUPÈRE LES INFORMATIONS SUR LE MEMBRE
			$d = $mysql->fetch_array();

			$resetKey = md5(md5(date('Y-m-d H:i:s')));
			$timeOutKey = time('Y-m-d H:i:s') + (3600 * 24);
			$timeOutKey = date('Y-m-d H:i:s', $timeOutKey);

			$mysql->query("
				INSERT INTO temp_members_password (
					member_id,
					reset_key,
					timeout
				)
				VALUES (
					'" . sql_quote($d['member_id']) . "',
					'" . $resetKey . "',
					'" . $timeOutKey . "'
				)
			", __FUNCTION__ );

			// ON ENVOI L'EMAIL CONTENANT LE LIEN POUR CRÉER LE NOUVEAU MOT DE PASSE
			require_once SYSTEM_PATH . 'classes/email.class.php';
			$emailClass = new email();

			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = $langArray['your_new_password'];
			$emailClass->message = emailTemplate(
				$langArray['your_new_password'],
				langMessageReplace(
					$langArray['email_reset_password_text'], array(
						'USERNAME' => $d['username']
					)
				),
				'http://' . $config['domain'] . '/password-recover?key=' . $resetKey,
				$langArray['new_password'],
				$langArray['email_no_spam']
			);

			$emailClass->to($d['email']);
			$emailClass->send();

			return true;
		}

		public function getTempPasswordKey($key) {
			global $mysql, $langArray, $config;

			$mysql->query("
				SELECT *
				FROM temp_members_password
				WHERE reset_key = '" . sql_quote($key) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return 'error_invalid_password_key';
			}

			$d = $mysql->fetch_array();

			if ($d['timeout'] < date('Y-m-d H:i:s')) {
				$mysql->query("
					DELETE FROM temp_members_password
					WHERE member_id = '" . intval($d['member_id']) . "' AND reset_key = '" . sql_quote($d['reset_key']) . "'
					LIMIT 1
				");

				return 'error_invalid_password_key';
			}

			$tempKey = array(
				'member_id' => $d['member_id'],
				'exist' => true
			);

			return $tempKey;
		}

		// CHANGER DE MOT DE PASSE (APRÈS UN MOT DE PASSE OUBLIÉ)
		public function changeLostPassword($member_id) {
			global $mysql, $langArray, $config;

			if (!isset($_POST['password']) || trim($_POST['password']) == '') {
				return 'error_fill_password';
			}

			if (!isset($_POST['password_confirm']) || trim($_POST['password_confirm']) == '') {
				return 'error_fill_password_confirm';
			}

			elseif (isset($_POST['password']) && isset($_POST['password_confirm']) && $_POST['password'] !== $_POST['password_confirm']) {
				return 'error_password_not_match';
			}

			$mysql->query("
				UPDATE members
				SET password = '" . md5(md5($_POST['password'])) . "'
				WHERE member_id = '" . intval($member_id) . "'
				LIMIT 1
			");

			$mysql->query("
				DELETE FROM temp_members_password
				WHERE member_id = '" . intval($member_id) . "'
			");

			return true;
		}

		// NOM D'UTILISATEUR OUBLIÉ
		public function lostUsername() {
			global $mysql, $langArray, $config;

			$mysql->query("
				SELECT *
				FROM members
				WHERE email = '" . sql_quote($_POST['email']) . "'
			", __FUNCTION__ );

			if ($mysql->num_rows() == 0) {
				return 'error_invalid_member_email';
			}

			$d = $mysql->fetch_array();

			require_once SYSTEM_PATH . 'classes/email.class.php';
			
			$emailClass = new email();
			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = $langArray['email_lost_username'];
			$emailClass->message = emailTemplate(
				$langArray['email_lost_username'],
				langMessageReplace(
					$langArray['email_lost_username_text'], array(
						'SITE_TITLE' => $meta['website_title'],
						'USERNAME' => $d['username']
					)
				),
				'http://' . $config['domain'] . '/login',
				$langArray['login'],
				$langArray['email_no_spam']
			);

			$emailClass->to($d['email']);
			$emailClass->send();

			return true;
		}

		// ÉDITER LE MOT DE PASSE
		public function editNewPassword() {
			global $mysql, $langArray;

			if (!isset($_POST['password']) || trim($_POST['password']) == '') {
				$error['password'] = $langArray['error_fill_password'];
			}

			else {
				$mysql->query("
					SELECT *
					FROM members
					WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' AND password = '" . md5(md5($_POST['password'])) . "'
				");

				if ($mysql->num_rows() == 0) {
					$error['password'] = $langArray['error_wrong_old_password'];
				}
			}

			if (!isset($_POST['new_password']) || trim($_POST['new_password']) == '') {
				$error['new_password'] = $langArray['error_fill_password'];
			}

			if (!isset($_POST['new_password_confirm']) || trim($_POST['new_password_confirm']) == '') {
				$error['new_password_confirm'] = $langArray['error_fill_password_confirm'];
			}

			elseif (isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && $_POST['new_password'] !== $_POST['new_password_confirm']) {
				$error['new_password_confirm'] = $langArray['error_password_not_match'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE members
				SET password = '" . md5(md5($_POST['new_password'])) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			return true;
		}

		public function editFeatureProduct() {
			global $mysql, $products;

			if (!isset($_POST['featured_product_id'])) {
				$_POST['featured_product_id'] = 0;
			}

			$_POST['featured_product_id'] = intval($_POST['featured_product_id']);

			if ($_POST['featured_product_id'] != 0 && !array_key_exists($_POST['featured_product_id'], $products)) {
				$_POST['featured_product_id'] = 0;
			}

			$mysql->query("
				UPDATE members
				SET featured_product_id = '" . intval($_POST['featured_product_id']) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['featured_product_id'] = $_POST['featured_product_id'];

			return true;
		}

		public function editExclusiveAuthor($type = 'true') {
			global $mysql;

			$mysql->query("
				UPDATE members
				SET exclusive_author = '" . sql_quote($type) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['exclusive_author'] = $type;

			return true;
		}

		public function editSaveLicense() {
			global $mysql, $langArray;

			if (!isset($_POST['license']) || !is_array($_POST['license'])) {
				$error = $langArray['error_choose_license'];
			}

			if (!isset($_POST['license']['personal'])) {
				$error = $langArray['error_choose_license_regular'];
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE members
				SET license = '" . json_encode($_POST['license']) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['license'] = $_POST['license'];

			return true;
		}

		public function editChangeAvatarImage() {
			global $mysql, $langArray, $config;

			$this->photoSizes = $config['avatar_photo_sizes'];
			$avatar = $this->upload('profile_image', $_SESSION['member']['member_id'] . '/', false, true, 'avatar');
			
			if (substr($avatar, 0, 6) == 'error_') {
				$this->avatarError = $langArray[$avatar];
			}

			$this->photoSizes = $config['homeimage_photo_sizes'];
			$homeimage = $this->upload('homepage_image', $_SESSION['member']['member_id'] . '/', false, 'cover');
			
			if (substr($homeimage, 0, 6) == 'error_') {
				$this->homeimageError = $langArray[$homeimage];
			}

			$setQuery = '';
			
			if ($avatar != '' && substr($avatar, 0, 6) != 'error_') {
				$this->deleteAvatar($_SESSION['member']['member_id']);
				$setQuery .= " avatar = '" . sql_quote($avatar) . "' ";
				$_SESSION['member']['avatar'] = $avatar;
			}

			if ($homeimage != '' && substr($homeimage, 0, 6) != 'error_') {
				$this->deleteHomeimage($_SESSION['member']['member_id']);
				
				if (!empty($setQuery)) {
					$setQuery .= ',';
				}

				$setQuery .= " homeimage = '" . sql_quote($homeimage) . "' ";
				$_SESSION['member']['homeimage'] = $homeimage;
			}

			if (!empty($setQuery)) {
				$mysql->query("
					UPDATE members
					SET $setQuery
					WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
					LIMIT 1
				");
			}

			return true;
		}

		public function editPersonalInformation() {
			global $mysql, $langArray;

			if (!isset($_POST['firstname']) || trim($_POST['firstname']) == '') {
				$error['firstname'] = $langArray['error_fill_firstname'];
			}

			if (!isset($_POST['lastname']) || trim($_POST['lastname']) == '') {
				$error['lastname'] = $langArray['error_fill_lastname'];
			}

			if (!isset($_POST['email']) || trim($_POST['email']) == '') {
				$error['email'] = $langArray['error_fill_email'];
			}

			elseif (!check_email($_POST['email'])) {
				$error['email'] = $langArray['error_not_valid_email'];
			}

			elseif ($this->isExistEmail($_POST['email'], $_SESSION['member']['email'])) {
				$error['email'] = $langArray['error_exist_email'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['company_name'])) {
				$_POST['company_name'] = '';
			}

			if (!isset($_POST['profile_title'])) {
				$_POST['profile_title'] = '';
			}

			if (!isset($_POST['profile_desc'])) {
				$_POST['profile_desc'] = '';
			}

			if (!isset($_POST['live_city'])) {
				$_POST['live_city'] = '';
			}

			if (!isset($_POST['country_id']) || trim($_POST['country_id']) == '') {
				$_POST['country_id'] = '0';
			}

			if (!isset($_POST['freelance'])) {
				$_POST['freelance'] = 'false';
			}

			$mysql->query("
				UPDATE members
				SET firstname = '" . sql_quote($_POST['firstname']) . "',
					lastname = '" . sql_quote($_POST['lastname']) . "',
					email = '" . sql_quote($_POST['email']) . "',
					company_name = '" . sql_quote($_POST['company_name']) . "',
					profile_title = '" . sql_quote($_POST['profile_title']) . "',
					profile_desc = '" . sql_quote($_POST['profile_desc']) . "',
					live_city = '" . sql_quote($_POST['live_city']) . "',
					country_id = '" . intval($_POST['country_id']) . "',
					freelance = '" . sql_quote($_POST['freelance']) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['firstname'] = $_POST['firstname'];
			$_SESSION['member']['lastname'] = $_POST['lastname'];
			$_SESSION['member']['email'] = $_POST['email'];
			$_SESSION['member']['company_name'] = $_POST['company_name'];
			$_SESSION['member']['profile_title'] = $_POST['profile_title'];
			$_SESSION['member']['profile_desc'] = $_POST['profile_desc'];
			$_SESSION['member']['live_city'] = $_POST['live_city'];
			$_SESSION['member']['country_id'] = $_POST['country_id'];
			$_SESSION['member']['freelance'] = $_POST['freelance'];

			return true;
		}

		public function editSocialInformation() {
			global $mysql, $langArray;

			require_once ROOT_PATH . '/applications/system/modeles/social.class.php';
			$socialClass = new social();
			$socialList = $socialClass->getAll(START, LIMIT, " url != '' AND visible = 'true' ");

			foreach ($_POST['social'] as $name => $username) {
				if (!array_key_exists($name, $socialList)) {
					$error = $langArray['error_unknow_social'];
				}
			}

			foreach ($socialList as $listID => $listValue) {
				if (!isset($_POST['social'][$listID])) {
					$_POST['social'][$listID] = '';
				}
			}

			if (isset($error)) {
				return $error;
			}

			$mysql->query("
				UPDATE members
				SET social = '" . json_encode($_POST['social']) . "'
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "'
				LIMIT 1
			");

			$_SESSION['member']['social'] = $_POST['social'];

			return true;
		}

		public function sendEmail() {
			global $mysql, $langArray, $member, $config;

			if (!isset($_POST['message']) || trim($_POST['message']) == '') {
				return $langArray['error_not_set_message'];
			}

			$mysql->query("
				INSERT INTO members_messages (
					from_id,
					from_email,
					to_id,
					message,
					datetime
				)
				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_SESSION['member']['email']) . "',
					'" . intval($member['member_id']) . "',
					'" . sql_quote($_POST['message']) . "',
					NOW()
				)
			");

			// ENVOYER L'EMAIL
			require_once SYSTEM_PATH . '/classes/email.class.php';
			
			$emailClass = new email();
			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = $langArray['email_profile_subject'];
			$emailClass->message = emailTemplate(
				$$langArray['email_profile_subject'],
				langMessageReplace(
					$langArray['email_profile_text'], array(
						'USERNAME' => $_SESSION['member']['username'],
						'EMAIL' => $_SESSION['member']['email'],
						'MESSAGE' => $_POST['message']
					)
				),
				'http://' . $config['domain'] . '/login',
				$langArray['reply'],
				$langArray['email_no_spam']
			);
			
			$emailClass->to($member['email']);
			$emailClass->send();

			return true;
		}

		// SUIVRE UN MEMBRE
		public function isFollow($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM members_followers
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' AND follow_id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function addFollow($id) {
			global $mysql;

			$mysql->query("
				INSERT INTO members_followers (
					member_id,
					follow_id
				)
				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . intval($id) . "'
				)
			");

			return true;
		}

		public function deleteFollow($id) {
			global $mysql;

			$mysql->query("
				DELETE FROM members_followers
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' AND follow_id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function followMember($id) {
			if ($this->isFollow($id)) {
				$this->deleteFollow($id);
			}

			else {
				$this->addFollow($id);
			}

			return true;
		}

		public function getFollowers($memberID, $start = 0, $limit = 0, $order = 'member_id ASC', $following = false) {
			global $mysql;

			$limitQuery = '';

			if (!empty($limit)) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($following) {
				$whereQuery = " follow_id = '" . intval($memberID) . "' ";
			}

			else {
				$whereQuery = " member_id = '" . intval($memberID) . "' ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM members_followers
				WHERE $whereQuery
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';
			
			while ($d = $mysql->fetch_array()) {
				if ($whereQuery != '') {
					$whereQuery .= " OR ";
				}

				if ($following) {
					$whereQuery .= " member_id = '" . intval($d['member_id']) . "' ";
				}
				else {
					$whereQuery .= " member_id = '" . intval($d['follow_id']) . "' ";
				}
			}

			$this->foundRows = $mysql->getFoundRows();

			return $this->getAll(0, 0, $whereQuery);
		}

		public function getFollowersID($memberID, $start = 0, $limit = 0, $order = 'member_id ASC', $following = false) {
			global $mysql;

			$limitQuery = '';
			
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($following) {
				$whereQuery = " follow_id = '" . intval($memberID) . "' ";
			}

			else {
				$whereQuery = " member_id = '" . intval($memberID) . "' ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM members_followers
				WHERE $whereQuery
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();
			
			while($d = $mysql->fetch_array()) {
				$return[] = $d;
			}

			return $return;
		}

		public function updateAuthor($id, $type) {
			global $mysql;

			$mysql->query("
				UPDATE members
				SET author = '" . sql_quote($type) . "', exclusive_author = '" . sql_quote($type) . "'
				WHERE member_id = '" . intval($id) . "'
				LIMIT 1
			");

			return true;
		}

		public function getMembersCount($whereQuery = '') {
			global $mysql;

			if ($whereQuery != '') {
				$whereQuery = " WHERE ".$whereQuery;
			}

			$mysql->query("
				SELECT *
				FROM members
				$whereQuery
			");

			return $mysql->num_rows();
		}

		public function getAuthorsCount($whereQuery = '') {
			global $mysql;

			$whereQuery = " WHERE author = 'true' ";

			$mysql->query("
				SELECT *
				FROM members
				$whereQuery
			");

			return $mysql->num_rows();
		}

		public function getStatistic($id) {
			global $mysql;

			$return = array();

			// DÉPÔT D'ARGENT
			$mysql->query("
				SELECT SUM(deposit) as sum
				FROM deposit
				WHERE member_id = '" . intval($id) . "' AND paid = 'true'
				GROUP BY member_id
			");

			if ($mysql->num_rows() == 0) {
				$return['deposit'] = 0;
			}

			else {
				$buff = $mysql->fetch_array();
				$return['deposit'] = $buff['sum'];
			}

			// $return['country'] = $mysql->fetch_array();

			// PRODUITS ACHETÉS
			$mysql->query("
				SELECT o.*, i.name AS product_name
				FROM orders AS o
				JOIN products AS i
				ON i.id = o.product_id
				WHERE o.member_id = '" . intval($id) . "' AND o.paid = 'true'
			");

			if ($mysql->num_rows() > 0) {
				$return['total'] = 0;
				
				while($d = $mysql->fetch_array()) {
					$return['products'][] = $d;
					$return['total'] += $d['price'];
				}
			}

			return $return;
		}

		public function getTotalReferals($id, $referal_id) {
			global $mysql;

			$mysql->query("
				SELECT COUNT(id) as sum
				FROM members_referals_count
				WHERE member_id = '" . intval($id) . "' AND referal_id = '" . intval($referal_id) . "'
				GROUP BY referal_id
				LIMIT 1
			");

			$buff = $mysql->fetch_array();
			
			if ($buff) {
				return $buff['sum'];
			}

			return 0;
		}
	}
?>
