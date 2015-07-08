<?php
    _setView(__FILE__);
    _setTitle($langArray['payment_rates']);

    $percentsClass = new percents();
    $percents = $percentsClass->getAll();
    abr('percents', $percents);
?>