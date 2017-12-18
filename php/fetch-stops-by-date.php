<?php
  // Fetch stops by driverId
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $driverID = '';
  if( isset($_POST['id']) ){
    $driverID = $_POST['id']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driverID = $parsed['id'];
  }
  
  $date = '';
  if( isset($_POST['date']) ){
    $date = $_POST['date']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $date = $parsed['date'];
  }

  $response = [];

  // Insert dispatcher
  $sql = "SELECT * FROM stops WHERE driverID = '$driverID' AND dateCreated = '$date'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = array("ID"=>$row["ID"], "address"=>$row["address"], "type"=>$row["type"], "size"=>$row["size"], "comment"=>$row["comment"], "parking"=>$row["parking"], "date"=>$row["dateCreated"], "time"=>$row["timeCreated"], "status"=>$row["status"], "dateFulfilled"=>$row["dateFulfilled"], "timeFulfilled"=>$row["timeFulfilled"] );
      array_push($response, $array);
    }
  } else {
    $response = '0 results';
  }

  echo json_encode($response);

  $conn->close();
?>