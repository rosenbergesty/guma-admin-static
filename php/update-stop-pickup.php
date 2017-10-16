<?php
  // Add stop
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $stopID = $_POST['stopId'];
  $containerNum = $_POST['container'];
  $date = $_POST['date'];
  $time = $_POST['time'];

  $response = [];
  
  // Insert dispatcher
  $sql = "UPDATE stops SET containerNum='$containerNum', status='complete', dateFulfilled='$date', timeFulfilled='$time' WHERE ID='$stopID'";
  if($conn->query($sql) === TRUE){
    $response = ['code'=>'200', 'response'=>'Successfully updated'];
  } else {
    $response = ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>