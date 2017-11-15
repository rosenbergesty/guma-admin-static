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
  
  $eta = '';
  if( isset($_POST['eta']) ){
    $eta = $_POST['eta']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $eta = $parsed['eta'];
  }

  $start = '';
  if( isset($_POST['start']) ){
    $start = $_POST['start']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $start = $parsed['start'];
  }

  $response = [];

  // Insert dispatcher
  $sql = "UPDATE stops SET eta='$eta', startTime='$start', status='in progress' WHERE ID='$id'";
  if ($conn->query($sql) === TRUE) {
    $response = ['code' => '200', 'message' => 'Successfully updated'];
  } else {
    $response = ['code' => '500', 'message' => 'Error updating: '.$conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>