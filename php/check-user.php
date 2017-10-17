<?php
    // Set up account
    header("Access-Control-Allow-Origin: *");

    // Connect to db
    require_once('connect.php');

    $response = [];

    if($_POST) {
        $username      = $_POST['username'];
        $password  = strip_tags($_POST['password']);
        // $password   = password_hash( $user_password, PASSWORD_BCRYPT, array('cost' => 11));
        
        $sql = "SELECT * FROM users WHERE username='".$username."'";
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

    }

    echo json_encode($response);

    $conn->close();
?>