<?php
  // Fetch driver by email
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
  $users = [];

  // Insert dispatcher
  $sql = "SELECT ID, name, email, password FROM drivers WHERE ID = '$id'";
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