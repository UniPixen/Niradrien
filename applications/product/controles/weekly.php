<?php
	_setView(__FILE__);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $productClass->membersWhere);
	abr('members', $members);

	$productClass = new product();

	$limit = 4;
	$start = (PAGE - 1) * $limit;

	$text = '';

	$product = $productClass->getAll($start, $limit, " status = 'active' AND weekly_to >= '" . date('Y-m-d') . "' ", "datetime DESC");

	$categories = $categoriesClass->getAll();

	if (PAGE > 1) {
		$text .= '<a href="javascript: void(0);" onclick="$.ajax({complete: function(request) { screenshotPreview(); hideLoading(); }, beforeSend: function() { showLoading(); }, dataType: &quot;script&quot;, type: &quot;post&quot;, url: &quot;/product/weekly/?p='.(PAGE-1).'&quot;}); return false;" title="" class="slider-control slider-prev"></a>';
	}

	else {
		$text .= '<span class="slider-control slider-prev-disabled"></span>';
	}

	$backslash = chr();

	if(is_array($product)) {
		$text .= '<ul id="weekly-featured-produits">';
		foreach($product as $i) {

			$a_kat = $i['categories'];

			$s_nazwy_kat = "";

			foreach ($a_kat as $a_k) {
				foreach ($a_k as $kat_id) {
					$s_nazwy_kat .= $categories[$kat_id]['name']." \ ";
				}
			}

			$s_nazwy_kat = mysql_real_escape_string(substr($s_nazwy_kat, 0, -3));

			$text .= '<li class="thumbnail"><a href="/product/' . $i['id'] . '" title="' . htmlspecialchars($i['name']) . '"><img alt="' . htmlspecialchars($i['name']) . '" class="landscape-image-magnifier preload no_preview" data-item-author="by ' . $members[$i['member_id']]['username'] . '" data-item-category="'. $s_nazwy_kat .'" data-item-cost="' . $currency['symbol'] . $i['price'] . ' " data-item-name="' . htmlspecialchars($i['name']) . '" data-preview-height="" data-preview-url="' . DATA_SERVER . '/uploads/products/' . $i['id'] . '/preview.jpg" data-preview-width="" src="' . DATA_SERVER . '/uploads/products/' . $i['id'] . '/' . $i['thumbnail'] . '" title=""  width="80" height="80"></a></li>';
		}
		$text .= '</ul>';
	}

	if((PAGE*$limit) <= $productClass->foundRows) {
		$text .= '<a href="javascript: void(0);" title="" onclick="$.ajax({complete: function(request) { screenshotPreview(); hideLoading(); }, beforeSend: function() { showLoading(); }, dataType: &quot;script&quot;, type: &quot;post&quot;, url: &quot;/product/weekly/?p='.(PAGE+1).'&quot;}); return false;" class="slider-control slider-next"></a>';
	}
	else {
		$text .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="slider-control slider-next-disabled"></span>';
	}


	die('
		jQuery("#weekly-featured-produits").html(\''.$text.'\');
	');

?>