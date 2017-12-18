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

        $driver = $row["ID"];

        // pending
        $pending_sql = "SELECT COUNT(*) FROM stops WHERE driverId='$driver' AND status='pending'";
        $pending_result = $conn->query($pending_sql);
        if($pending_result->num_rows > 0){
          while($pending_row = $pending_result->fetch_assoc()){
            $pending = $pending_row['COUNT(*)'];
            $array['pending'] = $pending;
          }
        }

        // total
        $total_sql = "SELECT COUNT(*) FROM stops WHERE driverId='$driver'";
        $total_result = $conn->query($total_sql);
        if($total_result->num_rows > 0){
          while($total_row = $total_result->fetch_assoc()){
            $total = $total_row['COUNT(*)'];
            $array['total'] = $total;
          }
        }

        // latest
        $date_sql = "SELECT MAX(STR_TO_DATE(dateFulfilled, '%d/%m/%Y')) AS latestDate FROM stops WHERE driverId='$driver'";

        $date_result = $conn->query($date_sql);
        if($date_result->num_rows > 0){
          while($date_row = $date_result->fetch_assoc()){
            $date = date('d/m/Y', strtotime($date_row['latestDate']));

            $time_sql = "SELECT MAX(STR_TO_DATE(timeFulfilled, '%H:%i:%s')) AS latestTime FROM stops WHERE dateFulfilled='$date'";
            $time_result = $conn->query($time_sql);
            if($time_result->num_rows > 0){
              while($time_row = $time_result->fetch_assoc()){
                $latest = $time_row['latestTime'];
                $array['latest'] = $latest;
              }
            }
          }
        }

      array_push($response, $array);
    }
  } else {
    $response = '0 results';
  }

  echo json_encode($response);

  $conn->close();
?>