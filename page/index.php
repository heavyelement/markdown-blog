<?php
    session_start();
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    if(!isset($_GET['p'])){
        header("Location: /");
    }
    else{
        require_once "../objects/main.php";

        $main = new Components;

        $meta = $main->renderHeader(true);

        $articles = new Articles;

        $articles->renderPage($meta,'');

        $main->renderFooter();
    }
?>