<?php
	require_once ROOT_PATH . '/applications/collections/modeles/favorites.class.php';
	$favoritesClass = new favorites();

	if (check_login_bool()) {
		$favoriteProduct = $favoritesClass->isInFavorites($product['id'], $_SESSION['member']['member_id']);
		abr('isFavorite', $favoriteProduct);

		if (isset($_POST['favorite'])) {
			if ($_POST['favorite'] == 'add') {
				if (!$favoriteProduct) {
					$s = $favoritesClass->add($product['id'], $_SESSION['member']['member_id']);

					if ($s === true) {
						refresh('/product/' . $productID . '/' . url($product['name']), $langArray['complete_favorite_product'], 'complete');
					}

					else {
						addErrorMessage($langArray['error_try_again'], '', 'error');
					}
				}

				else {
					addErrorMessage($langArray['error_try_again'], '', 'error');
				}
			}

			if ($_POST['favorite'] == 'delete') {
				if ($favoriteProduct) {
					$s = $favoritesClass->delete($product['id'], $_SESSION['member']['member_id']);

					if ($s === true) {
						refresh('/product/' . $productID . '/' . url($product['name']), $langArray['complete_delete_favorite_product'], 'complete');
					}

					else {
						addErrorMessage($langArray['error_try_again'], '', 'error');
					}
				}

				else {
					addErrorMessage($langArray['error_try_again'], '', 'error');
				}
			}
		}
	}
?>