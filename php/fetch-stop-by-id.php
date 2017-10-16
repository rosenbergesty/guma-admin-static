<?php
  // Fetch stop by id
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $id = $_POST['id'];
  $users = [];

  // Insert dispatcher
  $sql = "SELECT * FROM stops WHERE ID = '$id'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = $row;
      array_push($users, ["response" => "200", "data" => $array]);
    }
  } else {
    array_push($users, ["response" => "300", "data" => "Zero Results"]);
  }

  echo json_encode($users);

  $conn->close();
?>