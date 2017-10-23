<?php
  // Add stop
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $stopID = '';
  if( isset($_POST['stopId']) ){
    $stopID = $_POST['stopId']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $stopID = $parsed['stopId'];
  }

  $response = [];
  
  // Insert dispatcher
  $sql = "DELETE FROM stops WHERE ID = '$stopID'";
  if($conn->query($sql) === TRUE){
    array_push($response, ['code'=>'200', 'response'=>'Successfully deleted']);
  } else {
    array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
  }

  echo json_encode($response);

  $conn->close();
?>