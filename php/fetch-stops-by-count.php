<?php
  // Fetch stops by driverId
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $driverID = '';
  if( isset($_POST['driverID']) ){
    $driverID = $_POST['driverID']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driverID = $parsed['driverID'];
  }
  $dispatcher = '';
  if( isset($_POST['dispatcher']) ){
    $dispatcher = $_POST['dispatcher']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $dispatcher = $parsed['dispatcher'];
  }
  $count = '';
  if( isset($_POST['count']) ){
    $count = $_POST['count']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $count = $parsed['count'];
  }
  $total = '';
  if( isset($_POST['total']) ){
    $total = $_POST['total'];
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $total = $parsed['total'];
  }
  
  $response = [];

  // Insert dispatcher

  $sql = "SELECT * FROM (SELECT * FROM stops WHERE driverId='$driverID' AND dispatcherId='$dispatcher' ORDER BY STR_TO_DATE(dateCreated, '%d/%m/%Y') DESC LIMIT ".(int)$total.",".(int)$count.") sub ORDER BY STR_TO_DATE(dateCreated, '%d/%m/%Y') ASC ";

  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = array("ID"=>$row["ID"], "address"=>$row["address"], "type"=>$row["type"], "size"=>$row["size"], "date"=>$row["dateCreated"], "time"=>$row["timeCreated"], "status"=>$row["status"], "dateFulfilled"=>$row["dateFulfilled"], "timeFulfilled"=>$row["timeFulfilled"] );
      array_push($response, $array);
    }
  } else {
    $response = '0 results';
  }

  echo json_encode($response);

  $conn->close();
?>