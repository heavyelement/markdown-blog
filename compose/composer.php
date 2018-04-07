<?php
    session_start();
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    require_once "../objects/main.php";
    $main = new Components;

    $meta = $main->renderHeader("Saving Article...");

    $articles = new Articles;
    
    echo "<article>";
    $articles->saveArticle();
    echo "</article>";

    $main->renderFooter();
?>