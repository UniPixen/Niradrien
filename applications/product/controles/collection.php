<?php
	require_once ROOT_PATH . '/applications/collections/modeles/collections.class.php';
	$collectionsClass = new collections();

	if (check_login_bool()) {
		if (isset($_POST['add_collection'])) {
			$s = $collectionsClass->bookmark($productID);

			if ($s === true) {
				refresh('/product/' . $productID, $langArray['complete_bookmark_product'], 'complete');
			}

			else {
				addErrorMessage($s, '', 'error');
			}
		}

		else {
			$collections = $collectionsClass->getAll(0, 0, " member_id = '" . intval($_SESSION['member']['member_id']) . "' ");
			abr('bookCollections', $collections);
		}
	}
?>