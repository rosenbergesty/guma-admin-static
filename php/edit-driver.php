<?php
  // Edit driver
  header("Access-Control-Allow-Origin: *");
  
  // Connect to db
  require_once('connect.php');

  // Get data
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = strtolower($_POST['email']);

  $response = [];

  // Insert data
  $sql = "UPDATE drivers SET name='$name', email='$email' WHERE ID='$id'";
  if($conn->query($sql) === TRUE){
    $response = ['code'=>'200', 'response'=>'Successfully updated'];
  } else {
    $response = ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error];
  }

  echo json_encode($response);

  $conn->close();
?>