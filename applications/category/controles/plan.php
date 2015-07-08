<?php 
	_setView(__FILE__);
	_setTitle($langArray['all_categories']);

	$categoryID = get_id(1);
	if (is_numeric($categoryID) || $categoryID == 'all') {
		require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
		$categoriesClass = new category();
	
		$categories = $categoriesClass->getAll();
		abr('categories', $categories);		
	}
	else {
		$allCategories = $categoriesClass->getAllWithChilds(0, " visible = 'true' ");
		$categoriesbrowseList = $categoriesClass->generatebrowseList($allCategories);
		abr('categoriesbrowseList', $categoriesbrowseList);		
	}
?>