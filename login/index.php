<?php 
    session_start();
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    require_once "../objects/main.php";
    require_once "../objects/users.php";
    $main = new Components;

    $meta = $main->renderHeader("Composer");
    
    $msg = '';
    if(array_key_exists($_POST['username'], _USERS)){
        if(password_verify($_POST['password'], _USERS[$_POST['username']]['pword'])){
            $_SESSION = _USERS[$_POST['username']];
            $location = "Location: /" . http_build_query($_GET);
            header($location);
        }
        else{
            $msg = 'Username or password invalid';
        }
    }
    else{
        $msg = 'Username or password invalid';
    }
?>
<article>
    <?php echo $msg ?>
    <form name='login' method='post' action='/login/?<?php echo http_build_query($_GET); ?>' enctype="multipart/form-data">
        <input type='username' name='username' placeholder='Username'/>
        <input type='password' name='password' placeholder='Password'/>
        <button>Login</button>
    </form>
</article>
<?php
    $main->renderFooter();
?>