<?php
    // Fetch stops by driverId
    header("Access-Control-Allow-Origin: *");

    // Connect to db
    require_once('connect.php');

    $response = [];

    if($_POST) {
        $id      = $_POST['driverID'];
        $user_password  = mysql_real_escape_string($_POST['password']);
        
        //password_hash see : http://www.php.net/manual/en/function.password-hash.php
        $password   = password_hash( $user_password, PASSWORD_BCRYPT, array('cost' => 11));
        
        $sql = "SELECT * FROM drivers WHERE ID=$id";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                if(strlen($row['password']) < 8){
                    $sql = "UPDATE drivers SET password='$password' WHERE ID=$id";
                    if($conn->query($sql) === TRUE){
                        $response = ['status'=>'200', 'message'=>'Successfully set up account'];
                    } else {
                        $response = ['status'=>'500', 'message'=>'Error setting up account '.$conn->error];
                    }
                } else {
                    $response = ['status'=>'600', 'message'=>'The account is already set up.'];
                }
            }
        } else{
            $response = ['status'=>'400', 'message'=>'Invalid Driver ID'];
        }
    }

    echo json_encode($response);

    $conn->close();
?>