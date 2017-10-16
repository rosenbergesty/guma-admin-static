<?php
  // Fetch driver by email
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $id = $_POST['id'];
  $users = [];

  // Insert dispatcher
  $sql = "SELECT ID, name, email, password FROM dispatchers WHERE ID = '$id'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = array("ID"=>$row["ID"], "name"=>$row["name"], "email"=>$row["email"], "password"=>$row["password"] );
      array_push($users, ["response" => "200", "data" => $array]);
    }
  } else {
    array_push($users, ["response" => "300", "data" => "Zero Results"]);
  }

  echo json_encode($users);

  $conn->close();
?>