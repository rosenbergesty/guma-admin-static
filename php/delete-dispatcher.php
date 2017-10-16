<?php
  // Add stop
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $id = $_POST['id'];
  $response = [];
  
  // Insert dispatcher
  $sql = "DELETE FROM dispatchers WHERE ID = '$id'";
  if($conn->query($sql) === TRUE){
    array_push($response, ['code'=>'200', 'response'=>'Successfully deleted']);
  } else {
    array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
  }

  echo json_encode($response);

  $conn->close();
?>