<?php
  // Add stop
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $driverID = '';
  if( isset($_POST['driverID']) ){
    $driverID = $_POST['driverID']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driverID = $parsed['driverID'];
  }

  $dispatcher = '';
  if( isset($_POST['dispatcher']) ){
    $dispatcher = $_POST['dispatcher']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $dispatcher = $parsed['dispatcher'];
  }

  $address = '';
  if( isset($_POST['address']) ){
    $address = $_POST['address']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $address = $parsed['address'];
  }

  $type = '';
  if( isset($_POST['action']) ){
    $type = $_POST['action']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $type = $parsed['action'];
  }

  $size = '';
  if( isset($_POST['size']) ){
    $size = $_POST['size']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $size = $parsed['size'];
  }

  $date = '';
  if( isset($_POST['date']) ){
    $date = $_POST['date']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $date = $parsed['date'];
  }

  $time = '';
  if( isset($_POST['time']) ){
    $time = $_POST['time']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $time = $parsed['time'];
  }

  $comment = '';
  if( isset($_POST['comment']) ){
    $comment = $_POST['comment']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $comment = $parsed['comment'];
  }

  $response = [];
  
  // Insert dispatcher
  $sql = "INSERT INTO stops (driverId, dispatcherId, address, type, size, comment, dateCreated, timeCreated, status) VALUES ('$driverID', '$dispatcher', '$address', '$type', '$size', '$comment', '$date', '$time', 'pending')";
  if($conn->query($sql) === TRUE){
    array_push($response, ['code'=>'200', 'response'=>'Successfully added']);
  } else {
    array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
  }

  // Notify
  $sql = "SELECT * FROM deviceIds";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      sendFCM('New stop added.', $row['deviceId']);
    }
  } else {
    // no notifications...
  }

  function sendFCM($mess, $id) {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array (
            'to' => $id,
            'notification' => array (
                    "body" => $mess,
                    "title" => "Guma",
                    "icon" => "myicon"
            )
    );
    $fields = json_encode ( $fields );
    $headers = array (
            'Authorization: key=' . "AAAAjbaC910:APA91bEa3KayzaaUr3k1eMMfeiaRpCmbGqe73wFPvkuUf6YgLaDTOAaU9a6pDjEFDcm__PVD8sPp2ZBvPdfhHTMAyfb21_bM3bENZkzXWzJqWgPNwvcWqGBGCwPXXxlDMNx6zcuvCJ7u",
            'Content-Type: application/json');

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    curl_close ( $ch );
  }
  
  echo json_encode($response);

  $conn->close();
?>