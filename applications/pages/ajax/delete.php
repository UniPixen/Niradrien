<?php
    require_once '../../../config.php';
    require_once $config['root_path'] . '/system/functions.php';
    include_once $config['system_path'] . '/start_system.php';

    admin_login();

    if (isset($_POST['delete']) && isset($_POST['id']) && isset($_SESSION['member']['access']['pages'])) {
    	require_once ROOT_PATH . '/applications/pages/modeles/pages.class.php';

        $cms = new pages();
    	$cms->delete (intval($_POST['id']));

        die (
            json_encode(
                array_merge(
                    $_POST, array (
                        'status' => 'true'
                    )
                )
            )
        );
    }

    echo json_encode (
        array_merge (
            $_POST, array (
                'status' => 'unknown error'
            )
        )
    );

    die ();
?>