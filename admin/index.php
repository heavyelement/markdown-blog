<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once "../objects/main.php";
$main = new Components;

$meta = $main->renderHeader("Settings");
if(!empty($_SESSION['uname'])){
?>
<article>
    <form id='user' method='post' action='admin.php'>
        <?php
        if(isset($_GET['edit'])){
            echo "<input type='hidden' name='edit' value='true'/>";
        }
        ?>
        <h2>Users</h2>
        <div class='compose--meta'>
            <label class='block'>Username</label>
            <input type='text' name='username' />
        </div>
        <div class='compose--meta'>
            <label class='block'>Real Name</label>
            <input type='text' name='firstname' />
        </div>
        <div class='compose--meta'>
            <label class='block'>Password</label>
            <input type='password' name='password' />
        </div>
        <button name='addUser' value='true'>Add User</button>
    </form>
</article>
<?php
}
else{
    header("Location: /");
}
$main->renderFooter();
?>