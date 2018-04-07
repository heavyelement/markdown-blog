<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<pre>
<?php
require_once "../objects/users.php";
$users = new Users;
print_r($_POST);
if($_POST['addUser'] == true){
    $pword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user = [
        'uname' => $_POST['username'],
        'fname' => $_POST['firstname'],
        'pword' => $pword
    ];
    $ukey = $_POST['username'];
    $existingUsers = $users->loadUsers();
    if( !array_key_exists($user['uname'], $existingUsers) || isset($_GET['update']) ){
        $existingUsers[$ukey] = $user;
        print_r($existingUsers);
        if($users->saveUsers($user) == true){
            header("Location: /admin?success=uname");
        }
        else{
            header("Location: /admin?error=ufail");
        }
    }
    else{
        header("Location: /admin?error=uexist");
    }
}

?>
</pre>