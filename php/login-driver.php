<?php
    // Set up account
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");

    // Connect to db
    require_once('connect.php');

    $response = [];

    $username = '';
    if( isset($_POST['username']) ){
        $username = $_POST['username']; 
    } else {
        $requestBody = file_get_contents('php://input');
        $parsed = json_decode($requestBody, true);
        $username = $parsed['username'];
    }

    $password = '';
    if( isset($_POST['password']) ){
        $password = $_POST['password']; 
    } else {
        $requestBody = file_get_contents('php://input');
        $parsed = json_decode($requestBody, true);
        $password = $parsed['password'];
    }
    
    $sql = "SELECT * FROM drivers WHERE email='".$username."'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $success = false;
        while($row = $result->fetch_assoc()){
            $hash = $row[password];
            if(password_verify($password, $hash)){
                $response = [code=>200, data=>[$row]];
                $success = true;
            }
        }
        if(!$success){
            $response = [code=>300, message=>"Wrong Password"];
        }
    } else {
        $response = [code=>400, message=>"Wrong Username"];
    }

    echo json_encode($response);

    $conn->close();
?>