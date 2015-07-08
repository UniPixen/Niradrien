<?php
    require_once '../../../config.php';
    require_once $config['root_path'] . '/system/functions.php';
    include_once $config['system_path'] . '/start_system.php';
    require_once ROOT_PATH . 'applications/system/modeles/system.class.php';
    $systemClass = new system();
    $currency = $systemClass->getActiveCurrency();
	
	$html = '';

	require_once ROOT_PATH . '/applications/product/modeles/product.class.php';
	$productClass = new product();

	require_once ROOT_PATH . 'applications/category/modeles/category.class.php';
	$categoriesClass = new category();

	if (empty($_POST['category_id'])) {
		$_POST['category_id'] = '';
		header('Location: http://' . DOMAIN . '/error');
	}
	
	$categoryID = $_POST['category_id'];

	if (is_numeric($categoryID)) {
		$category = $categoriesClass->get($categoryID);

		if (!is_array($category) || $category['visible'] == 'false') {
			echo '<div class="span12">' . $langArray['unknow_category']. '</div>';
		}

		else {
			$whereQuery = " status = 'active' && categories LIKE '%," . intval($categoryID) . ",%' ";
			$product = $productClass->getAll(0, 18, $whereQuery, 'datetime DESC');

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();
			$members = $membersClass->getAll(0, 0, $productClass->membersWhere);

			require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
			$categoriesClass = new category();
			$categories = $categoriesClass->getAll();
			$category_product = '';

			$html .= '<ul class="derniers-produits-list">';
			if ($product > 0) {
				foreach ($product as $result) {
					foreach ($result['categories'] as $product_categories) {
						foreach ($product_categories as $get_categories_names) {
							$categories_separator = '';
							if ($get_categories_names != end($product_categories)) {
								$categories_separator = ' \ ';
							}
							$category_product .= $categories[$get_categories_names]['name'] . $categories_separator;
						}
					}

					$html .= '
						<li class="item span2">
							<a href="product/' . $result['id'] . '/' . url($result['name']) . '" class="preview">
								<img
									alt="' . $result['name'] . '"
									class="landscape-image-magnifier preload no_preview"
									data-item-author="' . $langArray['by'] . ' ' . $members[$result['member_id']]['username'] . '"
									data-item-category="' . $category_product . '"
									data-item-cost="' . $result['price'] . ' ' . $currency['symbol'] . '"
									data-item-name="' . $result['name'] . '"
									data-preview-height="" data-preview-url="' . $config['data_server'] . 'uploads/products/' . $result['id'] . '/preview.jpg"
									data-preview-width=""
									src="' . $config['data_server'] . 'uploads/products/' . $result['id'] . '/' . $result['thumbnail'] . '"
									title="' . $result['name'] . '"
								/>
							</a>
						</li>
					';

					$category_product = '';
				}
			}
				else {
					echo '<div class="span12">' . $langArray['no_records'] . '</div>';
				}
			$html .= '</ul>';

			echo $html;
		}

		$allCategories = $categoriesClass->getAll(0, 0, " visible = 'true' ");
		$categoryParent = $categoriesClass->getCategoryParents($allCategories, $categoryID);
		$categoryParent = explode(',', $categoryParent);
		$categoryParent = array_reverse($categoryParent);
		array_shift($categoryParent);
	}

	elseif ($categoryID == 'all') {
		$whereQuery = '';
		$product = $productClass->getAll(0, 18, " status = 'active' ".$whereQuery, 'datetime DESC');

		require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
		$membersClass = new members();
		$members = $membersClass->getAll(0, 0, $productClass->membersWhere);

		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();
		$categories = $categoriesClass->getAll();
		$category_product = '';
		
		$html .= '<ul class="derniers-produits-list">';
			if ($product > 0) {
				foreach ($product as $result) {
					foreach ($result['categories'] as $product_categories) {
						foreach ($product_categories as $get_categories_names) {
							$categories_separator = '';
							if ($get_categories_names != end($product_categories)) {
								$categories_separator = ' \ ';
							}
							$category_product .= $categories[$get_categories_names]['name'] . $categories_separator;
						}
					}

					$html .= '
						<li class="item span2">
							<a href="product/' . $result['id'] . '/' . url($result['name']) . '" class="preview">
								<img
									alt="' . $result['name'] . '"
									class="landscape-image-magnifier preload no_preview"
									data-item-author="' . $langArray['by'] . ' ' . $members[$result['member_id']]['username'] . '"
									data-item-category="' . $category_product . '"
									data-item-cost="' . $result['price'] . ' ' . $currency['symbol'] . '"
									data-item-name="' . $result['name'] . '"
									data-preview-height="" data-preview-url="' . $config['data_server'] . 'uploads/products/' . $result['id'] . '/preview.jpg"
									data-preview-width=""
									src="' . $config['data_server'] . 'uploads/products/' . $result['id'] . '/' . $result['thumbnail'] . '"
									title="' . $result['name'] . '"
								/>
							</a>
						</li>
					';

					$category_product = '';
				}
			}

			else {
				echo '<div class="span12">' . $langArray['no_records'] . '</div>';
			}

		$html .= '</ul>';

		echo $html;

		$allCategories = $categoriesClass->getAll(0, 0, " visible = 'true' ");
		$categoryParent = $categoriesClass->getCategoryParents($allCategories, $categoryID);
		$categoryParent = explode(',', $categoryParent);
		$categoryParent = array_reverse($categoryParent);
		array_shift($categoryParent);
	}

	else {
		echo '<div class="span12">' . $langArray['error'] . '</div>';
	}
?>