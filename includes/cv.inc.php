<?php

ini_set("session.use_trans_sid", "0");
session_start();

$session = &$_SESSION['cv_johan'];
if ( isset($_GET['lang'])
    && ($_GET['lang'] === 'fr'
    || $_GET['lang'] === 'en')
) {
    $session['lang'] = $_GET['lang'];
} else {
    if ( !isset($session['lang']) ) {
        $session['lang'] = 'fr';
    }
}

require_once 'lang/' . $session['lang'] . '.php';

?>
