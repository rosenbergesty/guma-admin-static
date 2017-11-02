<?php
  // Fetch drivers
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $response = [];

  $count = '';
  if( isset($_POST['count']) ){
    $count = $_POST['count']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $count = $parsed['count'];
  }

  // Insert dispatcher
  $sql = "SELECT * FROM (SELECT * FROM drivers ORDER BY id DESC LIMIT ".$count.") sub ORDER BY id ASC";
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