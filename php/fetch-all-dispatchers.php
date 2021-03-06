<?php
  // Fetch all dispatchers
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $response = [];

  // Fetch dispatchers
  $sql = "SELECT * FROM dispatchers";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = array("ID"=>$row["ID"], "name"=>$row["name"], "email"=>$row["email"], "password"=>$row["password"] );
      array_push($response, $array);
    }
  } else {
    $response = '0 results';
  }

  echo json_encode($response);

  $conn->close();
?>