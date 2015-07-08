<?php
	_setView(__FILE__);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
	abr('members', $members);

	$categories = $categoriesClass->getAll();
	$backslash = chr();

	$text = '';

	if (check_login_bool()) {
		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();

		$following = $membersClass->getFollowersID($_SESSION['member']['member_id']);
		if (is_array($following)) {
			$whereQuery = '';

			foreach ($following as $f) {
				if ($whereQuery != '') {
					$whereQuery .= ' OR ';
				}
				
				$whereQuery .= " member_id = '" . intval($f['follow_id']) . "' ";
			}

			$limit = 9;
			$start = (PAGE - 1) * $limit;

			$text .= ' <ul id="recently-followed-produits">';
				$followingProducts = $productClass->getAll($start, $limit, " status = 'active' AND ($whereQuery) ", "datetime DESC");
				if (is_array($followingProducts)) {
					foreach($followingProducts as $f) {
						$a_kat = $f['categories'];
						$s_nazwy_kat = '';

						foreach ($a_kat as $a_k) {
							foreach ($a_k as $kat_id) {
								$s_nazwy_kat .= $categories[$kat_id]['name']." \ ";
							}
						}

						$s_nazwy_kat = mysql_real_escape_string(substr($s_nazwy_kat, 0, -3));
						$text .= '
							<li class="thumbnail">
								<a href="/' . 'product/' . $f['id'] . '" onclick="">
									<img
										class="landscape-image-magnifier preload no_preview"
										data-item-author="by ' . $members[$f['member_id']]['username'] . '"
										data-item-category="'. $s_nazwy_kat .'" data-item-cost="' . $currency['symbol'] . $f['price'] . '"
										data-item-name="' . htmlspecialchars($f['name']) . '"
										data-preview-url="' . DATA_SERVER.'/uploads/products/' . $f['id'] . '/preview.jpg"
										src="' . DATA_SERVER . '/uploads/products/' . $f['id'] . '/' . $f['thumbnail'] . '"
										height="80"
										width="80"
									/>
								</a>
							</li>
						';
					}
				}
			$text .= '</ul>';

			// Générer le HTML
			if (PAGE > 1) {
				$text = '<a href="javascript: void(0);" onclick="$.ajax({complete: function(request) { screenshotPreview(); hideLoading(); }, beforeSend: function() { showLoading(); }, dataType: &quot;script&quot;, type: &quot;post&quot;, url: &quot;/' . 'members/following/?p=' . (PAGE - 1) . '&quot;}); return false;" class="slider-control slider-prev"></a>' . $text;
			}

			else {
				$text = ' <span class="slider-control slider-prev-disabled"></span>' . $text;
			}

			if ($productClass->foundRows > (PAGE * $limit)) {
				$text .= '<a href="javascript: void(0);" onclick="$.ajax({complete: function(request) { screenshotPreview(); hideLoading(); }, beforeSend: function() { showLoading(); }, dataType: &quot;script&quot;, type: &quot;post&quot;, url: &quot;/' . 'members/following/?p=' . (PAGE + 1) . '&quot;}); return false;" class="slider-control slider-next"></a>';
			}

			else {
				$text .= '<span class="slider-control slider-next-disabled"></span>';
			}

			$text = 'jQuery("#recently-followed-produits").html(\'' . $text . '\')';
		}
	}

	die ($text);
?>