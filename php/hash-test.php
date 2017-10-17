<?php
    // Set up account
    header("Access-Control-Allow-Origin: *");

    // Connect to db
    if($_POST) {
        $user_password  = strip_tags($_POST['password']);
        $hash = password_hash($user_password, PASSWORD_DEFAULT);
        // echo $hash;

        if(password_verify($user_password, $hash)){
            echo 'YES';
        } else {
            echo 'NO!';
        }
    }
?>