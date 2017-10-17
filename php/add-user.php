<?php
    // Set up account
    header("Access-Control-Allow-Origin: *");

    // Connect to db
    require_once('connect.php');

    $response = [];

    if($_POST) {
        $username      = $_POST['username'];
        $email         = $_POST['email'];
        $user_password  = strip_tags($_POST['password']);
        $password   = password_hash( $user_password, PASSWORD_BCRYPT, array('cost' => 11));
        
        $sql = "INSERT INTO users (username, email, password) VALUES ('".$usename."', '".$email."', '".$password."')";
        if($conn->query($sql) === TRUE){
            $response = [code=> 200, message=> 'success'];
        } else {
            $response = [code=> 500, message=> "Error: ".$sql." \n".$conn->error];
        }
    }

    echo json_encode($response);

    $conn->close();
?>