<?php
  // Fetch stops by driverId
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $driverID = '11';
  if( isset($_POST['driverID']) ){
    $driverID = $_POST['driverID']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driverID = $parsed['driverID'];
  }
  $dispatcher = '12';
  if( isset($_POST['dispatcher']) ){
    $dispatcher = $_POST['dispatcher']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $dispatcher = $parsed['dispatcher'];
  }
  $count = '100';
  if( isset($_POST['count']) ){
    $count = $_POST['count']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $count = $parsed['count'];
  }
  $total = '0';
  if( isset($_POST['total']) ){
    $total = $_POST['total'];
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $total = $parsed['total'];
  }
  
  $response = [];

  // Insert dispatcher
  // $sql = "SELECT * FROM stops WHERE driverID = '$driverID' AND dispatcherId = '$dispatcher' ORDER BY STR_TO_DATE(dateCreated,'%d%m%Y') DESC LIMIT ".(int)$total.", ".$count;

  $sql = "SELECT * FROM (SELECT * FROM stops ORDER BY STR_TO_DATE(dateCreated, '%d/%m/%Y') DESC LIMIT 0, 10) sub ORDER BY STR_TO_DATE(dateCreated, '%d/%m/%Y') ASC ";

  $result = $conn->query($sql);

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      echo '<p>'.$row['dateCreated'].'</p>';
    }
  }
  $conn->close();
?>