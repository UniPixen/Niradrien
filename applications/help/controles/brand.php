<?php 
	_setView(__FILE__);
	_setTitle($langArray['brand_guideline']);

	abr('logo_brand_text2', langMessageReplace($langArray['logo_brand_text2'], array('SITE_TITLE' => $meta['website_title'])));
?>