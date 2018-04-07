<?php
class Users{

    function loadUsers(){
        return json_decode(file_get_contents("../resources/cfg/users.json"),true);
    }

    function saveUsers($users){
        if(file_put_contents('../resources/cfg/users.json', json_encode($users))){ 
            return "Successfully wrote users";
        }
        else{
            return false;
        }
    }

    function getUserImage($user){
        echo _USERS[$user]['image'];
    }
    
    function doesUserExist($user){
        if(array_key_exists($user, _USERS)){
            return true;
        }
        elseif($user = ''){
            return false;
        }
        else{
            return false;
        }
    }
}

?>