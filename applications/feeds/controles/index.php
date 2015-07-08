<?php
	_setView(__FILE__);
	_setLayout('feeds');

	header('Content-type: application/xml; charset="utf-8"', true);
	mb_internal_encoding("UTF-8");
	
	$name = '';
	$link = '';
	$whereQuery = '';

	// Si on a un champ après feeds
	if (get_id(1) != '') {
		if (get_id(1) == 'category') {
			require_once ROOT_PATH . '/applications/category/modeles/category.class.php';

			$categoriesClass = new category();
			$category = $categoriesClass->getByKeyword(get_id(2));

			if ($category['id']) {
				$categoryID = $category['id'];
				$whereQuery .= " AND categories LIKE '%," . intval($categoryID) . ",%' ";
			}

			elseif (get_id(2) == 'all') {
				$whereQuery .= "";
			}

			else {
				header('Content-type: text/html; charset="utf-8"', true);
				die();
			}

			$name = $category['name'];
			$link .= 'category/' . get_id(1);

			if (get_id(2) == 'all') {
				$feed_type = $langArray['last_products'];
			}
			
			else {
				$feed_type = $langArray['last_products_category'] . ' ' . $category['name'];
			}
		}

		elseif (get_id(1) == 'member') {
			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';

			$membersClass = new members();
			$member = $membersClass->getByUsername(get_id(2));
			
			$memberID = $member['member_id'];
			$whereQuery .= " AND member_id = '" . intval($memberID) . "' ";
			$name = get_id(2);

			$link .= 'member/' . get_id(2);
			$feed_type = $langArray['feed_of'] . ' ' . $name;
		}

		// Si on écrit rien après member ou catégorie, on affiche une page blanche
		if (get_id(2) == '') {
			header('Content-type: text/html; charset="utf-8"', true);
			die();
		}
	}

	else {
		header('Content-type: text/html; charset="utf-8"', true);
		die();
	}

	echo '<?xml version="1.0" encoding="utf-8" ?>
			<rss version="2.0">
				<channel>
					<title>' . $meta['website_title'] . ' - ' . $feed_type . '</title>
					<link>http://' . $config['domain'] . '/feeds/' . $link . '</link>
					<description></description>
	';

	// Afficher les résultats
	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';

	$productClass = new product();
	$rows = $productClass->getAll(0, 20, " status = 'active' " . $whereQuery, "datetime DESC");

	if (is_array($rows)) {
		foreach($rows as $r) {
			echo '
				<item>
					<title><![CDATA[ ' . $r['name'] . ' ]]></title>
					<link>http://' . $config['domain'] . '/' . 'product/' . $r['id'] . '/' . url($r['name']) . '</link>
					<description><![CDATA[
			';

			if ($r['thumbnail'] != '') {
				echo '<a href="http://' . $config['domain'] . '/' . 'product/' . $r['id'] . '/' . url($r['name']) . '"><img src="' . $config['data_server'] . 'uploads/products/' . $r['id'] . '/' . $r['thumbnail'] . '" alt="' . $r['name'] . '" /></a>';
			}

			echo '<br />' . mb_substr(strip_tags($r['description']), 0, 200);
			
			if (empty($r['update_datetime'])) {
				$pubDate = $r['datetime'];
				$pubDate = date("D, d M Y H:i", strtotime($r['datetime']));
			}

			else {
				$pubDate = $r['update_datetime'];
				$pubDate = date("D, d M Y H:i", strtotime($r['update_datetime']));
			}
			
			echo ']]></description>
		        <pubDate>' . $pubDate . '</pubDate>
		        <guid>http://' . $config['domain'] . '/' . 'product/' . $r['id'] . '/' . url($r['name']) . '</guid>
		      </item>
			';
		}
	}

	echo '
		</channel>
		</rss>
	';
?>