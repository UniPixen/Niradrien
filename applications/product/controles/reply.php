<?php 
	_setView(__FILE__);
	_setLayout('blank');

	$commentID = get_id(2);
	
	require_once ROOT_PATH . '/applications/product/modeles/comments.class.php';
	$commentsClass = new comments();
	$comment = $commentsClass->get($commentID);

	if (!is_array($comment)) {
		addErrorMessage($langArray['wrong_comment'], '', 'error');
	}

	else {
		abr('show_form', 'yes');
		abr('comment', $comment);
	}
?>