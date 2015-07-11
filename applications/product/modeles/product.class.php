<?php
	class product {
		public $uploadFileDirectory = '';
		public $foundRows = 0;
		public $attributesWhere = '';
		public $attributeCategoriesWhere = '';
		public $membersWhere = '';

		public function __construct() {
			$this->uploadFileDirectory = 'products/';
		}

		public function getAll($start = 0, $limit = 0, $where = '', $order = 'datetime ASC') {
			global $mysql;

			$limitQuery = '';

			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if ($where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT *
				FROM products
				$where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$this->membersWhere = '';
			$return = array();

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

					$row++;
				}

				$return[$d['id']] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}

				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		public function getAllForUpdate($start = 0, $limit = 0, $where = '', $order = 'datetime ASC') {
			global $mysql;

			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}

			if( $where != '') {
				$where = " WHERE " . $where;
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM temp_products
				$where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$whereQuery = '';
			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;

		}

		public function get($id, $active = false) {
			global $mysql, $meta;

			$percents = 0;
			if (isset($meta['prepaid_price_discount'])) {
				$percents = $meta['prepaid_price_discount'];
			}

			$extended_price = 1;
			if (isset($meta['extended_price'])) {
				$extended_price = (int)$meta['extended_price'];
			}

			$sql = "
				SELECT *
				FROM products
				WHERE id = '" . intval($id) . "'
			";

			if ($active) {
				$sql .= " AND status = 'active'";
			}

			$mysql->query($sql);

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = $mysql->fetch_array();
			if (strpos($percents, '%') !== false) {
				$return['prepaid_price'] = $return['price'] + ( ( $return['price'] / 100 ) * (int)$percents );
				$return['paypal_price'] = $return['price'] + ( ( $return['price'] / 100 ) * (int)$percents );
				$return['your_profit'] = (int)( ( $return['price'] / 100 ) * (int)$percents );
			}
			else {
				$return['prepaid_price'] = $return['price'] + (int)$percents;
				$return['paypal_price'] = $return['price'] + (int)$percents;
				$return['your_profit'] = (int)$percents;
			}
			$return['extended_price'] = $return['price'] * $extended_price;

			$mysql->query($sql);

			$return['categories'] = array();
			if ($mysql->num_rows() > 0) {
				$row = 0;
				while($ca = $mysql->fetch_array()) {
					$categories = explode(',', $ca['categories']);

					foreach($categories as $c) {
						$c = trim($c);
						if($c != '') {
							$return['categories'][$row][$c] = $c;
						}
					}

					$row++;
				}
			}

			// CHARGER LES MOTS CLÉS
			$mysql->query("
				SELECT *
				FROM products_tags AS it
				JOIN tags AS t
				ON t.id = it.tag_id
				WHERE it.product_id = '" . intval($id) . "'
			");

			if($mysql->num_rows() > 0) {
				while($d = $mysql->fetch_array()) {
					$return['tags'][$d['tag_id']] = $d['name'];
				}
			}

			// CHARGER LES ATTRIBUTS
			$mysql->query("
				SELECT *
				FROM products_attributes
				WHERE product_id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() > 0) {
				while($d = $mysql->fetch_array()) {
					if (isset($return['attributes'][$d['category_id']])) {
						if (!is_array($return['attributes'][$d['category_id']])) {
							$val = $return['attributes'][$d['category_id']];
							unset($return['attributes'][$d['category_id']]);
							$return['attributes'][$d['category_id']][$val] = $val;
						}
						$return['attributes'][$d['category_id']][$d['attribute_id']] = $d['attribute_id'];

						if ($this->attributesWhere != '') {
							$this->attributesWhere .= " OR ";
						}

						$this->attributesWhere .= " id = '" . intval($d['attribute_id']) . "' ";
					}

					else {
						$return['attributes'][$d['category_id']] = $d['attribute_id'];

						if ($this->attributeCategoriesWhere != '') {
							$this->attributeCategoriesWhere .= " OR ";
						}

						$this->attributeCategoriesWhere .= " id = '" . intval($d['category_id']) . "' ";

						if ($this->attributesWhere != '') {
							$this->attributesWhere .= " OR ";
						}

						$this->attributesWhere .= " id = '" . intval($d['attribute_id']) . "' ";
					}
				}
			}

			return $return;
		}

		public function getForUpdate($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM temp_products
				WHERE id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = $mysql->fetch_array();

			// CHARGER LES MOTS CLÉS
			$mysql->query("
				SELECT *
				FROM temp_products_tags AS it
				JOIN tags AS t
				ON t.id = it.tag_id
				WHERE it.product_id = '" . intval($return['product_id']) . "'
			");

			if ($mysql->num_rows() > 0) {
				while($d = $mysql->fetch_array()) {
					$return['tags'][$d['type']][$d['tag_id']] = $d['name'];
				}
			}

			return $return;
		}

		public function add() {
			global $mysql, $langArray, $attributes;

			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_not_set_name'];
			}

			if (!isset($_POST['description']) || trim($_POST['description']) == '') {
				$error['description'] = $langArray['error_not_set_description'];
			}

			if (!isset($_POST['description_en']) || trim($_POST['description_en']) == '') {
				$error['description_en'] = $langArray['error_not_set_description_en'];
			}

			if (!isset($_POST['thumbnail']) || trim($_POST['thumbnail']) == '') {
				$error['thumbnail'] = $langArray['error_not_set_thumbnail'];
			}

			else {
				$file = pathinfo($_POST['thumbnail']);

				if(strtolower($file['extension']) != 'jpg' && strtolower($file['extension']) != 'png') {
					$error['thumbnail'] = $langArray['error_thumbnail_jpg'];
				}

				elseif(!file_exists(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['thumbnail'])) {
					$error['thumbnail'] = $langArray['error_thumbnail_jpg'];
				}
			}

			if (!isset($_POST['theme_preview']) || trim($_POST['theme_preview']) == '') {
				$error['theme_preview'] = $langArray['error_not_set_theme_preview'];
			}

			else {
				$file = pathinfo($_POST['theme_preview']);

				if (strtolower($file['extension']) != 'zip') {
					$error['theme_preview'] = $langArray['error_theme_preview_zip'];
				}

				elseif (!file_exists(DATA_SERVER_PATH . '/uploads/temporary/'.$_POST['theme_preview'])) {
					$error['theme_preview'] = $langArray['error_theme_preview_zip'];
				}

				else {
					$zip = new ZipArchive;
					$res = $zip->open(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['theme_preview']);

					if ($res === TRUE) {
						$images_count = 0;
						for($i = 0; $i < $zip->numFiles; $i++) {
						    if (strtolower(strrchr($zip->getNameIndex($i), '.')) == '.jpg' ||
						        strtolower(strrchr($zip->getNameIndex($i), '.')) == '.jpeg' ||
						        strtolower(strrchr($zip->getNameIndex($i), '.')) == '.png') {
						        $images_count++;
						    }
						}

						$zip->close();

						if($images_count < 1) {
							$error['theme_preview'] = $langArray['error_theme_preview_zip_images'];
						}

					}

					else {
						$error['theme_preview'] = $langArray['error_theme_preview_zip'];
					}
				}
			}

			if (!isset($_POST['main_file']) || trim($_POST['main_file']) == '') {
				$error['main_file'] = $langArray['error_not_set_main_file'];
			}

			else {
				$file = pathinfo($_POST['main_file']);
				if (strtolower($file['extension']) != 'zip') {
					$error['main_file'] = $langArray['error_main_file_zip'];
				}
				elseif (!file_exists(DATA_SERVER_PATH . '/uploads/temporary/'.$_POST['main_file'])) {
					$error['main_file'] = $langArray['error_main_file_zip'];
				}
			}

			if (!isset($_POST['category'])) {
				$error['category'] = $langArray['error_not_set_category'];
			}

			elseif (!is_array($_POST['category'])) {
				$error['category'] = $langArray['error_not_set_category'];
			}

			elseif(!count($_POST['category'])) {
				$error['category'] = $langArray['error_not_set_category'];
			}

			if (is_array($attributes)) {
				$attributesError = false;

				foreach($attributes as $a) {
					if (!isset($_POST['attributes'][$a['id']])) {
						$attributesError = true;
						break;
					}
				}

				if ($attributesError) {
					$error['attributes'] = $langArray['error_set_all_attributes'];
				}
			}

			if (!isset($_POST['tags']) || trim($_POST['tags']) == '') {
				$error['tags'] = $langArray['error_not_set_tags'];
			}

			if (!isset($_POST['source_license'])) {
				$error['source_license'] = $langArray['error_not_set_source_license'];
			}

			if (isset($_POST['demo_url']) && trim($_POST['demo_url']) && filter_var($_POST['demo_url'], FILTER_VALIDATE_URL) === false) {
				$error['demo_url'] = $langArray['error_demo_url'];
			}

			if ($_POST['suggested_price'] && !preg_match('#^\d+(?:\.\d{1,})?$#', $_POST['suggested_price'])) {
				$error['suggested_price'] = $langArray['error_suggested_price'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['demo_url'])) {
				$_POST['demo_url'] = '';
			}

			if (!isset($_POST['comments_to_reviewer'])) {
				$_POST['comments_to_reviewer'] = '';
			}

			require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
			$categoriesClass = new category();

			$allCategories = $categoriesClass->getAll();

			if (is_array($_POST['category'])) {
				foreach($_POST['category'] AS $category_id) {
					$categories = $categoriesClass->getCategoryParents($allCategories, $category_id);
					$categories = explode(',', $categories);
					array_pop($categories);
					$categories = array_reverse($categories);
					$categories = ',' . implode(',', $categories) . ',';
				}
			}

			else {
				$categories = $categoriesClass->getCategoryParents($allCategories, $_POST['category']);
				$categories = explode(',', $categories);
				array_pop($categories);
				$categories = array_reverse($categories);
				$categories = ',' . implode(',', $categories) . ',';
			}

			$mysql->query("
				INSERT INTO products (
					member_id,
					name,
					description,
					description_en,
					thumbnail,
					theme_preview,
					main_file,
					main_file_name,
					categories,
					demo_url,
					reviewer_comment,
					datetime,
					status,
					suggested_price
				)
				VALUES (
					'" . intval($_SESSION['member']['member_id']) . "',
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['description']) . "',
					'" . sql_quote($_POST['description_en']) . "',
					'thumbnail.png',
					'" . sql_quote($_POST['theme_preview']) . "',
					'" . sql_quote($_POST['main_file']) . "',
					'" . sql_quote($_SESSION['temp']['uploaded_files'][$_POST['main_file']]['name']) . "',
					'" . sql_quote($categories) . "',
					'" . sql_quote($_POST['demo_url']) . "',
					'" . sql_quote($_POST['comments_to_reviewer']) . "',
					NOW(),
					'queue',
					'" . (float)$_POST['suggested_price'] . "'
				)
			");

			$productID = $mysql->insert_id();


			// COPIER LES FICHIERS DU DOSSIER TEMPORAIRE
			recursive_mkdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/');

			copy (DATA_SERVER_PATH.'/uploads/temporary/' . $_POST['thumbnail'], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/' . 'thumbnail.png');
			copy (DATA_SERVER_PATH.'/uploads/temporary/' . $_POST['theme_preview'], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/' . 'preview.zip');
			copy (DATA_SERVER_PATH.'/uploads/temporary/' . $_POST['main_file'], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/' . $_POST['main_file']);

			$zip = new ZipArchive;
			$res = $zip->open(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/' . 'preview.zip');

			// http://php.net/manual/fr/ziparchive.extractto.php
			// VOIR SI JE PEUX RENOMMER LES IMAGES CONTENUES DANS LE ZIP
			if ($res === TRUE) {
				$zip->extractTo(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/');
				$zip->close();
			}

			// REDIMENSIONNER LES VIGNETTES ET CRÉER DES IMAGES D'APERÇU
			require_once SYSTEM_PATH . '/classes/image.class.php';
			$imageClass = new Image();

			$imageClass->crop(DATA_SERVER_PATH.'/uploads/' . $this->uploadFileDirectory . $productID.'/' . 'thumbnail.png', 170, 170);
	    	$files = scandir(DATA_SERVER_PATH.'/uploads/' . $this->uploadFileDirectory . $productID.'/preview/');

	    	$previewFile = '';
	    	if (is_array($files)) {
	    		$i = 1;
	    		foreach ($files as $f) {
	    			if (file_exists(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/' . $f)) {
	    				$fileInfo = pathinfo(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/' . $f);

						if (strpos($f, 'png')) {
						    rename(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/' . $f, DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/product-preview-' . $i . '.png');
						    $f = 'product-preview-' . $i . '.png';
						    $i++;
						}

						if (strpos($f, 'MACOSX')) {
							rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/__MACOSX/');
						}

						if (!isset($fileInfo['extension'])) {
							$fileInfo['extension'] = '';
						}

	    				if (strtolower($fileInfo['extension']) == 'jpg' || strtolower($fileInfo['extension']) == 'png') {
	    					$previewFile = $previewFile ? $previewFile : $f;
	    				}
	    			}
	    		}
	    	}

			if ($previewFile != '') {
				$imageClass->forceType(2);
				$size = getimagesize(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/' . $previewFile);
				$imageClass->crop(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview/' . $previewFile, 770, $size[1], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview.jpg');
			}

			if (file_exists(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview.zip')) {
				unlink(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $productID . '/preview.zip');
			}

			// SUPPRIMER LES FICHIERS TEMPORAIRES
			if (is_array($_SESSION['temp']['uploaded_files'])) {
				foreach($_SESSION['temp']['uploaded_files'] as $f) {
					@unlink(DATA_SERVER_PATH.'/uploads/temporary/' . $f['filename']);
				}
			}
			unset($_SESSION['temp']['uploaded_files']);

			// INSÉRER LES MOTS CLÉS
			require_once ROOT_PATH.'/applications/tags/modeles/tags.class.php';
			$tagsClass = new tags();

			$arr = explode(',', $_POST['tags']);
			foreach($arr as $tag) {
				$tag = trim(strtolower($tag));
				if ($tag != '') {
					$tagID = $tagsClass->getTagID($tag);

					$mysql->query("
						INSERT INTO products_tags (
							product_id,
							tag_id
						)
						VALUES (
							'" . intval($productID) . "',
							'" . intval($tagID) . "'
						)
					");
				}
			}

			// INSÉRER LES ATTRIBUTS
			$_POST['attributes'] = (array)(isset($_POST['attributes']) ? $_POST['attributes'] : array());
			foreach($_POST['attributes'] as $cID => $a) {
				if (is_array($a)) {
					foreach($a as $ai) {
						$mysql->query("
							INSERT INTO products_attributes (
								product_id,
								attribute_id,
								category_id
							)
							VALUES (
								'" . intval($productID) . "',
								'" . sql_quote($ai) . "',
								'" . sql_quote($cID) . "'
							)
						");
					}
				}

				else {
					$mysql->query("
						INSERT INTO products_attributes (
							product_id,
							attribute_id,
							category_id
						)
						VALUES (
							'" . intval($productID) . "',
							'" . sql_quote($a) . "',
							'" . sql_quote($cID) . "'
						)
					");
				}
			}

			return true;
		}

		public function edit_upload($id) {
			global $mysql, $langArray, $product;

			if (isset($_POST['thumbnail']) && trim($_POST['thumbnail']) != '') {
				$file = pathinfo($_POST['thumbnail']);

				if(strtolower($file['extension']) != 'jpg' && strtolower($file['extension']) != 'png') {
					$error['thumbnail'] = $langArray['error_thumbnail_jpg'];
				}

				elseif(!file_exists(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['thumbnail'])) {
					$error['thumbnail'] = $langArray['error_thumbnail_jpg'];
				}
			}

			if (isset($_POST['theme_preview']) && trim($_POST['theme_preview']) != '') {
				$file = pathinfo($_POST['theme_preview']);

				if (strtolower($file['extension']) != 'zip') {
					$error['theme_preview'] = $langArray['error_theme_preview_zip'];
				}

				elseif (!file_exists(DATA_SERVER_PATH . '/uploads/temporary/'.$_POST['theme_preview'])) {
					$error['theme_preview'] = $langArray['error_theme_preview_zip'];
				}

				else {
					$zip = new ZipArchive;
					$res = $zip->open(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['theme_preview']);

					if ($res === TRUE) {
						$images_count = 0;
						for ($i = 0; $i < $zip->numFiles; $i++) {
						    if (strtolower(strrchr($zip->getNameIndex($i), '.')) == '.jpg' ||
						      strtolower(strrchr($zip->getNameIndex($i), '.')) == '.jpeg' ||
						      strtolower(strrchr($zip->getNameIndex($i), '.')) == '.png') {
						    	$images_count++;
						    }
						}

						$zip->close();

						if ($images_count < 1) {
							$error['theme_preview'] = $langArray['error_theme_preview_zip_images'];
						}
					}

					else {
						$error['theme_preview'] = $langArray['error_theme_preview_zip'];
					}
				}
			}

			if (isset($_POST['main_file']) && trim($_POST['main_file']) != '') {
				$file = pathinfo($_POST['main_file']);

				if (strtolower($file['extension']) != 'zip') {
					$error['main_file'] = $langArray['error_main_file_zip'];
				}

				elseif(!file_exists(DATA_SERVER_PATH.'/uploads/temporary/' . $_POST['main_file'])) {
					$error['main_file'] = $langArray['error_main_file_zip'];
				}
			}

			if (!isset($_POST['tags']) || trim($_POST['tags']) == '') {
				$error['tags'] = $langArray['error_not_set_tags'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['comments_to_reviewer'])) {
				$_POST['comments_to_reviewer'] = '';
			}

			// COPIER LES FICHIERS DU DOSSIER DES FICHIER TEMPORAIRES
			recursive_mkdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/temp/');

			$colQuery = '';
			$valQuery = '';

			if (isset($_POST['thumbnail']) && trim($_POST['thumbnail']) != '') {
				copy(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['thumbnail'], DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $id . '/temp/' . 'thumbnail.png');

				$colQuery .= " thumbnail, ";
				$valQuery .= " 'thumbnail.png', ";
			}

			if (isset($_POST['theme_preview']) && trim($_POST['theme_preview']) != '') {
				copy(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['theme_preview'], DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $id . '/temp/preview.zip');

				$colQuery .= " theme_preview, ";
				$valQuery .= " 'preview.zip', ";
			}

			if (isset($_POST['main_file']) && trim($_POST['main_file']) != '') {
				copy(DATA_SERVER_PATH . '/uploads/temporary/' . $_POST['main_file'], DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $id . '/temp/' . $_POST['main_file']);

				$colQuery .= " main_file, main_file_name, ";
				$valQuery .= " '" . sql_quote($_POST['main_file']) . "', '" . sql_quote($_SESSION['temp']['uploaded_files'][$_POST['main_file']]['name']) . "', ";
			}

			$mysql->query("
				INSERT INTO temp_products (
					product_id,
					name,
					$colQuery
					reviewer_comment,
					datetime
				)
				VALUES (
					'" . intval($id) . "',
					'" . sql_quote($product['name']) . "',
					$valQuery
					'" . sql_quote($_POST['comments_to_reviewer']) . "',
					NOW()
				)
			");

			// SUPPRIMER LES FICHIERS TEMPORAIRES
			if (isset($_SESSION['temp']['uploaded_files']) && is_array($_SESSION['temp']['uploaded_files'])) {
				foreach($_SESSION['temp']['uploaded_files'] as $f) {
					@unlink(DATA_SERVER_PATH.'/uploads/temporary/' . $f['filename']);
				}
			}

			unset($_SESSION['temp']['uploaded_files']);

			// INSÉRER LES MOTS CLÉS
			require_once ROOT_PATH . '/applications/tags/modeles/tags.class.php';
			$tagsClass = new tags();

			foreach($_POST['tags'] as $type => $tags) {
				$arr = explode(',', $tags);

				foreach($arr as $tag) {
					$tag = trim($tag);
					if($tag != '') {
						$tagID = $tagsClass->getTagID($tag);

						$mysql->query("
							INSERT INTO temp_products_tags (
								product_id,
								tag_id,
								type
							)
							VALUES (
								'" . intval($id) . "',
								'" . intval($tagID) . "',
								'" . sql_quote($type) . "'
							)
						");
					}
				}
			}

			return true;
		}

		public function edit($id, $fromAdmin = false) {
			global $mysql, $langArray, $attributes;

			if (!isset($_POST['description']) || trim($_POST['description']) == '') {
				$error['description'] = $langArray['error_not_set_description'];
			}

			if (!isset($_POST['description_en']) || trim($_POST['description_en']) == '') {
				$error['description_en'] = $langArray['error_not_set_description_en'];
			}

			if($fromAdmin && (!isset($_POST['price']) || trim($_POST['price']) == '' || $_POST['price'] == '0')) {
				$error['price'] = $langArray['error_not_set_price'];
			}

			if (isset($_POST['demo_url']) && trim($_POST['demo_url']) && filter_var($_POST['demo_url'], FILTER_VALIDATE_URL) === false) {
				$error['demo_url'] = $langArray['error_demo_url'];
			}

			if (!isset($_POST['category'])) {
				$error['category'] = $langArray['error_not_set_category'];
			}

			elseif ( !is_numeric($_POST['category']) && !is_array($_POST['category']) ) {
				$error['category'] = $langArray['error_not_set_category'];
			}

			if (is_array($attributes)) {
				$attributesError = false;

				foreach($attributes as $a) {
					if (!isset($_POST['attributes'][$a['id']])) {
						$attributesError = true;
						break;
					}
				}

				if ($attributesError) {
					$error['attributes'] = $langArray['error_set_all_attributes'];
				}
			}

			if (isset($error)) {
				return $error;
			}

			$setQuery = '';

			if ($fromAdmin) {
				$setQuery .= " price = '" . sql_quote($_POST['price']) . "', ";

				if (isset($_POST['free_file'])) {
					$mysql->query("
						UPDATE products
						SET free_file = 'false'
					");
					$setQuery .= " free_file = 'true', ";
				}

				if (isset($_POST['weekly_to']) && trim($_POST['weekly_to']) != '') {
					$setQuery .= " weekly_to = '" . sql_quote($_POST['weekly_to']) . "', ";
				}
			}

			if (!isset($_POST['demo_url'])) {
				$_POST['demo_url'] = '';
			}

			if (!isset($_POST['free_request'])) {
				$_POST['free_request'] = 'false';
			}

			require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
			$categoriesClass = new category();
			$allCategories = $categoriesClass->getAll();

			if (is_array($_POST['category'])) {
				foreach($_POST['category'] AS $category_id) {
					$categories = $categoriesClass->getCategoryParents($allCategories, $category_id);
					$categories = explode(',', $categories);
					array_pop($categories);
					$categories = array_reverse($categories);
					$categories = ',' . implode(',', $categories) . ',';
				}
			}

			else {
				$categories = $categoriesClass->getCategoryParents($allCategories, $_POST['category']);
				$categories = explode(',', $categories);
				array_pop($categories);
				$categories = array_reverse($categories);
				$categories = ',' . implode(',', $categories) . ',';
			}

			$mysql->query("
				UPDATE products
				SET description = '" . sql_quote($_POST['description']) . "',
					description_en = '" . sql_quote($_POST['description_en']) . "',
					free_request = '" . sql_quote($_POST['free_request']) . "',
					$setQuery
					categories = '" . sql_quote($categories) . "',
					demo_url = '" . sql_quote($_POST['demo_url']) . "'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			// RENOUVELLER LES ATTRIBUTS
			$mysql->query("
				DELETE FROM products_attributes
				WHERE product_id = '" . intval($id) . "'
			");

			$_POST['attributes'] = (array)(isset($_POST['attributes']) ? $_POST['attributes'] : array());

			foreach($_POST['attributes'] as $cID => $a) {
				if (is_array($a)) {
					foreach ($a as $ai) {
						if (!trim($ai)) {
							continue;
						}

						$mysql->query("
							INSERT INTO products_attributes (
								product_id,
								attribute_id,
								category_id
							)
							VALUES (
								'" . intval($id) . "',
								'" . sql_quote($ai) . "',
								'" . sql_quote($cID) . "'
							)
						");
					}
				}

				else {
					if (!trim($a)) {
						continue;
					}

					$mysql->query("
						INSERT INTO products_attributes (
							product_id,
							attribute_id,
							category_id
						)
						VALUES (
							'" . intval($id) . "',
							'" . sql_quote($a) . "',
							'" . sql_quote($cID) . "'
						)
					");
				}
			}

			if ($fromAdmin) {
				if (isset($_POST['free_file'])) {
					$this->addUserStatus($id, 'freefile');

					$mysql->query("
						UPDATE products
						SET free_file = 'true'
						WHERE id = '" . intval($id) . "'
						LIMIT 1
					");
				}

				else {
					$mysql->query("
						UPDATE products
						SET free_file = 'false'
						WHERE id = '" . intval($id) . "'
						LIMIT 1
					");
				}

				if (isset($_POST['weekly_to']) && trim($_POST['weekly_to']) != '') {
					$this->addUserStatus($id, 'featured');
				}
			}

			return true;
		}

		public function delete($id, $unapprove = false) {
			global $mysql;
			
			recursive_rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/', true);
			$data = $this->get($id);

			// SUPPRIMER LE FICHIER
			$mysql->query("
				DELETE FROM products
				WHERE id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_attributes
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_collections
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_comments
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_faqs
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_rates
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM products_tags
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM temp_products
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM temp_products_tags
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM orders
				WHERE product_id = '" . intval($id) . "'
			");

			$mysql->query("
				DELETE FROM favorites
				WHERE product_id = '" . intval($id) . "'
			");

			if (!$unapprove) {
				$mysql->query("
					UPDATE members
					SET products = products - 1
					WHERE member_id = '" . intval($data['member_id']) . "'
					LIMIT 1
				");
			}

			return true;
		}

		public function deleteUpdate($id) {
			global $mysql;

			recursive_rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $id . '/temp/', true);

			// SUPPRIMER LES MOTS CLÉS DU FICHIER TEMPORAIRE
			$mysql->query("
				DELETE FROM temp_products_tags
				WHERE product_id = '" . intval($id) . "'
			");

			// SUPPRIMER LE FICHIER TEMPORAIRE
			$mysql->query("
				DELETE FROM temp_products
				WHERE product_id = '" . intval($id) . "'
			");

			return true;
		}

		// FONCTIONS ADMINISTRATEUR
		public function approve($id) {
			global $mysql, $data, $langArray;

			if ($data['status'] == 'active') {
				return true;
			}

			if (!isset($_POST['price']) || trim($_POST['price']) == '' || $_POST['price'] == '0') {
				return $langArray['error_set_price'];
			}

			$_POST['price'] = str_replace(',', '.', $_POST['price']);

			$mysql->query("
				UPDATE products
				SET price = '" . sql_quote($_POST['price']) . "',
					status = 'active'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			$mysql->query("
				UPDATE members
				SET products = products + 1
				WHERE member_id = '" . intval($data['member_id']) . "'
				LIMIT 1
			");

			return true;
		}

		public function unapprove($id) {
			global $mysql, $data, $langArray, $config;

			if ($data['status'] == 'active') {
				return true;
			}

			if (!isset($_POST['comment_to_member']) || trim($_POST['comment_to_member']) == '') {
				return $langArray['error_set_comment_to_member'];
			}

			$mysql->query("
				UPDATE products
				SET status = 'unapproved'
				WHERE id = '" . intval($id) . "'
				LIMIT 1
			");

			$mysql->query("
				UPDATE members
				SET products = products + 1
				WHERE member_id = '" . intval($data['member_id']) . "'
				LIMIT 1
			");

			// ENVOYER UN EMAIL AU MEMBRE
			require_once SYSTEM_PATH . '/classes/email.class.php';

			$emailClass = new email();
			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = $langArray['email_unapprove_product_subject'];
			$emailClass->message = emailTemplate(
				$langArray['email_unapprove_product_subject'],
				langMessageReplace(
					$langArray['email_unapprove_product_text'], array(
						'PRODUCT_NAME' => $data['name'],
						'COMMENT' => $_POST['comment_to_member']
					)
				),
				'',
				'',
				$langArray['email_no_spam']
			);

			$emailClass->to($data['member']['email']);
			$emailClass->send();

			return true;
		}

		public function unapproveDelete($id) {
			global $mysql, $data, $langArray, $config;

			if ($data['status'] == 'active') {
				return true;
			}

			if (!isset($_POST['comment_to_member']) || trim($_POST['comment_to_member']) == '') {
				return $langArray['error_set_comment_to_member'];
			}

			$this->delete($id, true);

			// ENVOYER UN EMAIL AU MEMBRE
			require_once SYSTEM_PATH . '/classes/email.class.php';

			$emailClass = new email();
			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = '[' . $config['domain'] . '] ' . $langArray['email_unapprove_delete_product_subject'];
			$emailClass->message = langMessageReplace($langArray['email_unapprove_delete_product_text'], array(
				'THEMENAME' => $data['name'],
				'COMMENT' => $_POST['comment_to_member']
			));
			$emailClass->to($data['member']['email']);
			$emailClass->send();

			return true;
		}


		public function approveUpdate($id) {
			global $mysql, $data, $product, $langArray;

			$setQuery = '';

			if (isset($_POST['price']) && is_numeric($_POST['price']) && $_POST['price'] != '0') {
				$_POST['price'] = str_replace(',', '.', $_POST['price']);
				$setQuery .= " price = '" . sql_quote($_POST['price']) . "', ";
			}

			// CHARCHER LA CLASSE IMAGE
			require_once SYSTEM_PATH . '/classes/image.class.php';
			$imageClass = new Image();

			if ($data['thumbnail'] != '') {
				$setQuery .= " thumbnail = '" . sql_quote($data['thumbnail']) . "', ";

				unlink(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $product['thumbnail']);
				copy(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/temp/' . $data['thumbnail'], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $data['thumbnail']);

				$imageClass->crop(DATA_SERVER_PATH.'/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $data['thumbnail'], 170, 170);
			}

			if ($data['theme_preview'] != '') {
				$setQuery .= " theme_preview = '" . sql_quote($data['theme_preview']) . "', ";

				if (file_exists(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory.$product['id'] . '/' . $product['theme_preview'])) {
					unlink(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory.$product['id'] . '/' . $product['theme_preview']);
				}
				recursive_rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory.$product['id'] . '/preview/', true);
				copy(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory.$product['id'] . '/temp/' . $data['theme_preview'], DATA_SERVER_PATH.'/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $data['theme_preview']);

				$zip = new ZipArchive;
				$res = $zip->open(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $data['theme_preview']);

				if ($res === TRUE) {
					$zip->extractTo(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/');
					$zip->close();
				}

				$files = scandir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/');
				$previewFile = '';

				if (is_array($files)) {
					$test = array();
					$i = 1;
					foreach($files as $f) {
						if (file_exists(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/' . $f)) {
							$fileInfo = pathinfo(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/' . $f);
							if (strpos($f, 'png')) {
							    rename(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/' . $f, DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/product-preview-' . $i . '.png');
							    $f = 'product-preview-' . $i . '.png';
							    $i++;
							}
							if (strpos($f, 'MACOSX')) {
								rmdir(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/__MACOSX/');
							}
							if (!isset($fileInfo['extension'])) {
								$fileInfo['extension'] = '';
							}
							if (strtolower($fileInfo['extension']) == 'jpg' || strtolower($fileInfo['extension']) == 'png') {
								$previewFile = $previewFile ? $previewFile : $f;
							}
						}
						
					}
				}

				if ($previewFile != '') {
					$imageClass->forceType(2);
					$size = getimagesize(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/' . $previewFile);
					$imageClass->crop(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview/' . $previewFile, 770, $size[1], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview.jpg');
				}

				if (file_exists(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview.zip')) {
					unlink(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/preview.zip');
				}

				// die();
			}

			if ($data['main_file'] != '') {
				$setQuery .= "
					main_file = '" . sql_quote($data['main_file']) . "',
					main_file_name = '" . sql_quote($data['main_file_name']) . "',
				";

				@unlink(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $product['main_file']);
				copy(DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/temp/' . $data['main_file'], DATA_SERVER_PATH . '/uploads/' . $this->uploadFileDirectory . $product['id'] . '/' . $data['main_file']);
			}

			$mysql->query("
				UPDATE products
				SET $setQuery
					status = 'active',
					update_datetime = NOW()
				WHERE id = '" . intval($product['id']) . "'
				LIMIT 1
			");

			// INSÉRER LES MOTS CLÉS
			$mysql->query("
				DELETE FROM products_tags
				WHERE product_id = '" . intval($product['id']) . "'
			");

			require_once ROOT_PATH . '/applications/tags/modeles/tags.class.php';
			$tagsClass = new tags();

			foreach ($data['tags'] as $tags) {
				foreach ($tags as $tagID => $tag) {
					$mysql->query("
						INSERT INTO products_tags (
							product_id,
							tag_id,
						)
						VALUES (
							'" . intval($product['id']) . "',
							'" . intval($tagID) . "',
						)
					");
				}
			}

			$this->deleteUpdate($product['id']);

			return true;
		}

		public function unapproveDeleteUpdate($id) {
			global $mysql, $product, $data, $langArray, $config;

			if (!isset($_POST['comment_to_member']) || trim($_POST['comment_to_member']) == '') {
				return $langArray['error_set_comment_to_member'];
			}

			$this->deleteUpdate($product['id']);

			// ENVOYER UN EMAIL AU MEMBRE
			require_once SYSTEM_PATH . '/classes/email.class.php';
			$emailClass = new email();
			$emailClass->fromEmail = 'no-reply@' . $config['domain'];
			$emailClass->subject = '[' . $config['domain'] . '] ' . $langArray['email_unapprove_delete_product_update_subject'];
			$emailClass->message = langMessageReplace($langArray['email_unapprove_delete_product_update_text'], array(
				'THEMENAME' => $product['name'],
				'COMMENT' => $_POST['comment_to_member']
			));
			$emailClass->to($product['member']['email']);
			$emailClass->send();

			return true;
		}

		public function isInUpdateQueue($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM temp_products
				WHERE product_id = '" . intval($id) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		public function getProductsCount() {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products
				WHERE status = 'active'
			");

			return $mysql->num_rows();
		}


		public function isRate($id) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products_rates
				WHERE product_id = '" . intval($id) . "' AND member_id = '" . intval($_SESSION['member']['member_id']) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return $mysql->fetch_array();
		}

		public function rate($id, $rate) {
			global $mysql, $product;

			require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
			$membersClass = new members();

			$member = $membersClass->get($product['member_id']);

			$row = $this->isRate($id);
			
			// Si la note est juste une modification d'une note déjà postée
			if (is_array($row)) {
				$oldNote = $row['rate'];
				$newNote = $rate;

				$product['score'] =  $product['score'] - $oldNote + $newNote;
				$product['rating'] = $product['score'] / $product['votes'];
				$product['rating'] = round($product['rating']);

				$member['score'] = $member['score'] - $oldNote + $newNote;
				$member['rating'] = $member['score'] / $member['votes'];
				$member['rating'] = round($member['rating']);

				// NOTE DU MEMBRE
				$mysql->query("
					UPDATE members
					SET rating = '" . intval($member['rating']) . "',
						score = '" . intval($member['score']) . "',
					WHERE member_id = '" . intval($member['member_id']) . "'
				");

				$mysql->query("
					UPDATE products
					SET rating = '" . intval($product['rating']) . "',
						score = '" . intval($product['score']) . "'
					WHERE id = '" . intval($id) . "'
				");

				$mysql->query("
					UPDATE products_rates
					SET rate = '" . intval($newNote) . "',
						datetime = NOW()
					WHERE product_id = '" . intval($id) . "' AND member_id = '" . intval($_SESSION['member']['member_id']) . "'
				");
			}

			// Si c'est une nouvelle note
			else {
				$product['votes'] = $product['votes'] + 1;
				$product['score'] = $product['score'] + $rate;
				$product['rating'] = $product['score'] / $product['votes'];
				$product['rating'] = round($product['rating']);

				// NOTE DU MEMBRE
				$member['votes'] = $member['votes'] + 1;
				$member['score'] = $member['score'] + $rate;
				$member['rating'] = $member['score'] / $member['votes'];
				$member['rating'] = round($member['rating']);

				$mysql->query("
					UPDATE members
					SET rating = '" . intval($member['rating']) . "',
						score = '" . intval($member['score']) . "',
						votes = '" . intval($member['votes']) . "'
					WHERE member_id = '" . intval($member['member_id']) . "'
				");

				$mysql->query("
					UPDATE products
					SET rating = '" . intval($product['rating']) . "',
						score = '" . intval($product['score']) . "',
						votes = '" . intval($product['votes']) . "'
					WHERE id = '" . intval($id) . "'
				");

				$mysql->query("
					INSERT INTO products_rates (
						product_id,
						member_id,
						rate,
						datetime
					)
					VALUES (
						'" . intval($id) . "',
						'" . intval($_SESSION['member']['member_id']) . "',
						'" . intval($rate) . "',
						NOW()
					)
				");
			}

			return $product;
		}

		public function getRates($where = '') {
			global $mysql;

			if ($where != '') {
				$where = " AND ($where) ";
			}

			$mysql->query("
				SELECT *
				FROM products_rates
				WHERE member_id = '" . intval($_SESSION['member']['member_id']) . "' $where
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['product_id']] = $d;
			}

			return $return;
		}

		public function getProductRates($productID) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM products_rates
				WHERE product_id = " . intval($productID) . "
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$return = array();

			while($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}

			return $return;
		}

		public function getTagProducts($tagID, $start = 0, $limit = 0, $where = '', $order = 'datetime DESC') {
			global $mysql;

			$limitQuery = '';
			
			if ($limit != 0) {
				$limitQuery = " LIMIT $start,$limit ";
			}

			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS i.*
				FROM products_tags AS it
				JOIN products AS i
				ON i.id = it.product_id
				WHERE it.tag_id = '" . intval($tagID) . "' $where
				ORDER BY $order
				$limitQuery
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			$this->membersWhere = '';
			$return = array();

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

					$row++;
				}

				$return[$d['id']] = $d;

				if ($this->membersWhere != '') {
					$this->membersWhere .= ' OR ';
				}

				$this->membersWhere .= " member_id = '" . intval($d['member_id']) . "' ";
			}

			$this->foundRows = $mysql->getFoundRows();
			return $return;
		}

		private function addUserStatus($id, $type = 'freefile') {
			$product = $this->get($id);

			if (is_array($product)) {
				if (!$this->isExistUserStatus($product['member_id'], $type)) {
					$this->insertUserStatus($product['member_id'], $type);
				}
			}

			return true;
		}

		private function isExistUserStatus($id, $type) {
			global $mysql;

			$mysql->query("
				SELECT *
				FROM members_status
				WHERE member_id = '" . intval($id) . "' AND status = '" . sql_quote($type) . "'
			");

			if ($mysql->num_rows() == 0) {
				return false;
			}

			return true;
		}

		private function insertUserStatus($id, $type) {
			global $mysql;

			$mysql->query("
				INSERT INTO members_status (
					member_id,
					status,
					datetime
				)
				VALUES (
					'" . intval($id) . "',
					'" . sql_quote($type) . "',
					NOW()
				)
			");

			return true;
		}
	}
?>
