<?php

class Components{
    function renderHeader($title){
        
        // Defines variables
        define('_SITETITLE', 'The Linux Gamer');
        // define('_PATH', '/home/gmoody88/public_html/');
        define('_PATH', '/var/www/html/');
        define('_ARTICLES', _PATH . 'articles/');
        define('_META', _PATH . 'meta/');
        define('_ARTICLEGETVAR', 'id');
        define('_OBJECTS', _PATH . 'objects/');
        define('_RESOURCES', _PATH . 'resources/');
        define('_VENDOR', _RESOURCES . 'vendor/');
        define('_IMAGES', _RESOURCES . 'images/');
        define('_FONTS', _RESOURCES . 'fonts/');
        define('_PARSEDOWN', _VENDOR . 'Parsedown.php');
        define('_ARTICLEOBJ', _OBJECTS . 'articles.php');
        define('_CFG', _RESOURCES . 'cfg/');
        define('_USERS', json_decode(file_get_contents(_CFG . 'users.json'),true));
        require_once _PARSEDOWN;
        require_once _ARTICLEOBJ;

        // The following decodes the metadata for the article
        if( isset($_GET['id']) ){

            $artobj = new Articles;
            $meta = $artobj->parseArticle($_GET['id']);
            $titler = $meta['title'];

        }
        elseif( isset($_GET['p'])){

            $artobj = new Articles;
            $meta = $artobj->parseArticle(".".$_GET['p']);
            $titler = $meta['title'];

        }
        else{
            $meta = null;
            $titler = $title;

        }

?>

    <!DOCTYPE html>
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
        <head>
            <link rel="stylesheet" href="/css/ionicons.min.css">
            <!-- <link rel="stylesheet" href="/css/normalize.css"> -->
            <link rel="stylesheet" href="/css/main.css">
            <link rel="icon" href="/favicon.ico">
            <link href="https://fonts.googleapis.com/css?family=Raleway:800|Roboto:400,400i,900" rel="stylesheet">
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>
            <?php
                echo $titler . " | " . _SITETITLE;
            ?>
            </title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            
        </head>
        <body>
        <nav id="sticky-nav-bar" class="gradient-background">
            <a href="/page/?p=about">
                <img src="/tlg-logo.png" width="300px" alt="About">
            </a>
            <ul class="main-navigation">
                <li id="home-link" class="nav-option">
                    <a href="/">Home</a>
                </li>
                <li id="ethics-link" class="nav-option">
                    <a href="/page/?p=ethics">Ethics</a>
                </li>
                <li id="contacts-link" class="nav-option">
                    <a href="/page/?p=contacts">Contact</a>
                </li>
                <li id="donate" class="nav-option">
                    <a href="/page/?p=contribute">Contribute</a>
                </li>
                <li id="video-link" class="nav-option">
                    <a href="/predictions/">Predictions<span class="hide-until-mouse ion-link"></span></a>
                </li>
            </ul>
            <div id="housekeeping">
                <div class="soc-group">
                    <a href="http://twitter.com/thelinuxgamer" class="ion-social-twitter"></a>
                    <label>twitter</label>
                </div>
                <div class="soc-group">
                    <a href="http://youtube.com/thelinuxgamer" class="ion-social-youtube"></a>
                    <label>youtube</label>
                </div>
                <div class="soc-group">
                    <a href="http://twitch.tv/xondak" class="ion-social-twitch-outline"></a>
                    <label>twitch</label>
                </div>
                <div class="soc-group">
                    <a href="http://instagram.com/thelinuxgamer" class="ion-social-instagram-outline"></a>
                    <label>instagram</label>
                </div>
                <div class="soc-group">
                    <a href="https://github.com/heavyelement" class="ion-social-github"></a>
                    <label>github</label>
                </div>
                <!-- <div class="soc-group">
                    <a href="http://facebook.com/thelinuxgamer" class="ion-social-facebook"></a>
                    <label>facebook</label>
                </div> -->
            </div>
        </nav>
<?php
    
        // This returns the meta variable to the main script.
        return $meta;
    
    } // end renderHeader

    function renderFooter(){

?>

            <!--[if lt IE 7]>
                <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
            
            </body>
    </html>

<?php
    } // end renderFooter

} // end Components

?>