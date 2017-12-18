<?php
  // Add Device ID
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $id = '';
  if( isset($_POST['id']) ){
    $id = $_POST['id']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $id = $parsed['id'];
  }

  $device = '';
  if( isset($_POST['device']) ){
    $device = $_POST['device']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $device = $parsed['device'];
  }

  $response = [];

  // Insert deviceId
  $sql = "INSERT INTO deviceIds (driverId, deviceId) VALUES ('$id', '$device')";
  if($conn->query($sql) === TRUE){
    array_push($response, ['code'=>'200', 'response'=>'Successfully added']);
  }  else {
    array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
  }

  echo json_encode($response);
  $conn->close();
  
?>