<?php
  // Fetch all stops
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Get data
  $response = [];

  // Insert dispatcher
  $sql = "SELECT * FROM stops";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $array = array("ID"=>$row["ID"], "driverId"=>$row["driverId"], "dispatcherId"=>$row["dispatcherId"], "address"=>$row["address"], "type"=>$row["type"], "size"=>$row["size"], "status"=>$row["status"], "containerNum"=>$row["containerNum"], "containerNum2"=>$row["containerNum2"], "borough"=>$row["borough"], "comments"=>$row["comments"], "signature"=>$row["signature"], "dateCreated"=>$row["dateCreated"], "timeCreated"=>$row["timeCreated"], "dateFulfilled"=>$row["dateFulfilled"], "timeFulfilled"=>$row["timeFulfilled"] );
      array_push($response, $array);
    }
  } else {
    $response = '0 results';
  }

  echo json_encode($response);

  $conn->close();
?>