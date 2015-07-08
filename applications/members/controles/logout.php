<?php
	_setView (ROOT_PATH . '/applications/members/controles/login.php');

    unset ($_SESSION['member']);
    unset ($_SESSION['token']);
    unset ($_SESSION['tmp']);
	refresh ('/');
?>