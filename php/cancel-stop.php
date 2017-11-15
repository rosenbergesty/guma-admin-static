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

  $response = [];

  // Insert dispatcher
  $sql = "UPDATE stops SET eta=NULL, startTime=NULL, status='pending' WHERE ID='$id'";
  if ($conn->query($sql) === TRUE) {
    $response = ['code' => '200', 'message' => 'Successfully updated'];
  } else {
    $response = ['code' => '500', 'message' => 'Error updating: '.$conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>