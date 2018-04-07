<?php
    session_start();
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    require_once "objects/main.php";

    $main = new Components;

    $meta = $main->renderHeader("This Is My Webzone");

    $articles = new Articles;

    $articles->renderMulti('meta/');

    $main->renderFooter();

?>