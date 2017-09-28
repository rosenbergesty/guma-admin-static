<?php
    header("Access-Control-Allow-Origin: *");
    require_once('connect.php');

    $response = [];

    if($_POST) {
        $id      = $_POST['driverID'];
        $user_password  = mysql_real_escape_string($_POST['password']);
        
        //password_hash see : http://www.php.net/manual/en/function.password-hash.php
        $password   = password_hash( $user_password, PASSWORD_BCRYPT, array('cost' => 11));
        
        try
        {
            $sql = "SELECT * FROM drivers WHERE ID = '$driverID'";
            $result = $conn->query($sql);
            $count = $result->num_rows;
            
            if($count!=0){
                if($result[0]["password"] != ""){
                    $sql = "UPDATE drivers SET password='$password' WHERE ID='$driverID' ";
                    if($conn->query($sql) === TRUE){
                        $response = ['stutus'=>'200', 'message'=>'Successfully set up account'];
                    } else {
                        $response = ['status'=>'500', 'message'=>'Error setting up account. '.$conn->error];
                    }   
                } else {
                    $response = ['status'=>'500', 'message'=>'Account alread set up'];
                }
            }
            else{
                $response = ['status'=>'500', 'message'=>'Invalid Driver ID'];
            }

        }
        catch(PDOException $e){
            $response = ['status'=>'500', 'message'=>$e->getMessage()];
        }
    }

    echo json_encode($response);

    $conn->close();
?>