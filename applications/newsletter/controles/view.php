<?php
    _setView(__FILE__);
    _setLayout('newsletter');

    $newsletterID = get_id(2);
    $newsletterClass = new newsletter();
    $newsletter = $newsletterClass->get($newsletterID);

    if (!is_array($newsletter)) {
        refresh('/');
    }
    abr('newsletter', $newsletter);

    $template = $newsletterClass->getTemplate();
    abr('newsletter', langMessageReplace($template, array (
        'DOMAIN' => $config['domain'],
        'newsletterID' => $newsletterID,
        'EMAIL' => 'noemail',
        'CONTENT' => $newsletter['text']
        )
    ));
?>