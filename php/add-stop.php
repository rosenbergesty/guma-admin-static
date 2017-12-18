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

  $parking = '';
  if( isset($_POST['parking']) ){
    $parking = $_POST['parking']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $parking = $parsed['parking'];
  }

  $response = [];
  
  // Insert stop
  if(strlen($address) > 0){
    $sql = "INSERT INTO stops (driverId, dispatcherId, address, type, size, comment, parking, dateCreated, timeCreated, status) VALUES ('$driverID', '$dispatcher', '$address', '$type', '$size', '$comment', '$parking', '$date', '$time', 'pending')";
    if($conn->query($sql) === TRUE){
      array_push($response, ['code'=>'200', 'response'=>'Successfully added']);
      sendNotification($conn, $driverID, $type, $address);
    } else {
      array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
    } 
  }

  // Send Notification
  function sendNotification($conn, $driver, $stopType, $stop){
    $sql = "SELECT deviceId FROM deviceIds WHERE driverId = 11";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $content = array( 
          "en" => $stopType.' - '.$stop
        );
        $headings = array(
          "en" => 'Stop Added'
        );

        $fields = array(
          'app_id' => "9f03606d-9cc7-46a9-8083-f25629aba6be",
          'include_player_ids' => array($row['deviceId']),
          'large_icon' =>"ic_launcher_round.png",
          'contents' => $content,
          'headings' => $headings,
          'ios_badgeType' => 'Increase',
          'ios_badgeCount' => '1'
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic YzNkMzdlYTktZDI4Yy00YmY0LWJhM2EtMDAyNWIzMGEwMTFl'));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

          $msg = curl_exec($ch);
          curl_close($ch);

          // $return["allresponses"] = $msg;
          // $message = json_encode( $return);

          // $response = 'sent to '.$row['deviceId'].'stop: '.$stop;
      }
    } 
  }

  echo json_encode($response);
  $conn->close();
?>