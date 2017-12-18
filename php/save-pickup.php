<?php
  // Fetch save pickup
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
  
  $time = '';
  if( isset($_POST['time']) ){
    $time = $_POST['time']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $time = $parsed['time'];
  }

  $date = '';
  if( isset($_POST['date']) ){
    $date = $_POST['date']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $date = $parsed['date'];
  }

  $container = '';
  if( isset($_POST['container']) ){
    $container = $_POST['container']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $container = $parsed['container'];
  }

  $image = '';
  if( isset($_POST['pic']) ){
    $image = $_POST['pic']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $image = $parsed['pic'];
  }

  $response = [];

  // Insert dispatcher
  $sql = "UPDATE stops SET timeFulfilled='$time', dateFulfilled='$date', containerNum='$container', image='$image', status='completed' WHERE ID='$id'";
  if ($conn->query($sql) === TRUE) {
    $response = ['code' => '200', 'message' => 'Successfully updated'];

    // Drop Ticket
  } else {
    $response = ['code' => '500', 'message' => 'Error updating: '.$conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>