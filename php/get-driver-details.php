<?php
  // Fetch stops by driverId
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data
  $driver = '11';
  if( isset($_POST['driver']) ){
    $driver = $_POST['driver']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driver = $parsed['driver'];
  }

  $response = [];

  $pending = '';
  $total = '';
  $last = '';


  // pending
  $sql = "SELECT COUNT(*) FROM stops WHERE status='pending' AND driverId='$driver'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $pending = $row['COUNT(*)'];
    }
  }

  // total
  $sql = "SELECT COUNT(*) FROM stops WHERE driverId='$driver'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $total = $row['COUNT(*)'];
    }
  }

  // latest
  $date_sql = "SELECT MAX(STR_TO_DATE(dateFulfilled, '%d/%m/%Y')) AS latestDate FROM stops WHERE driverId='$driver'";

  $result = $conn->query($date_sql);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $date = date('d/m/Y', strtotime($row['latestDate']));

      $sql = "SELECT MAX(STR_TO_DATE(timeFulfilled, '%H:%i:%s')) AS latestTime FROM stops WHERE dateFulfilled='$date'";
      $result2 = $conn->query($sql);
      if($result2->num_rows > 0){
        while($row2 = $result2->fetch_assoc()){
          $latest = $row2['latestTime'];
        }
      }
    }
  }

  $response = ['driver' => $driver, 'pending'=>$pending, 'total'=>$total, 'latest'=>$latest];
  echo json_encode($response);

  $conn->close();
?>