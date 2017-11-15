<?php
  // Fetch stops by driverId
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

  $comments = '';
  if( isset($_POST['comments']) ){
    $comments = $_POST['comments']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $comments = $parsed['comments'];
  }

  $signature = '';
  if( isset($_POST['signature']) ){
    $signature = $_POST['signature']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $signature = $parsed['signature'];
  }

  $response = [];

  // Insert dispatcher
  $sql = "UPDATE stops SET timeFulfilled='$time', dateFulfilled='$date', containerNum='$container', comments='$comments', signature='$signature', status='complete' WHERE ID='$id'";
  if ($conn->query($sql) === TRUE) {
    $response = ['code' => '200', 'message' => 'Successfully updated'];

    // Drop Ticket
  } else {
    $response = ['code' => '500', 'message' => 'Error updating: '.$conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>