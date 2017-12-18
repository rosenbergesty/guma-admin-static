<?php
  // Send Notification
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // Get data

  $driver = '11';
  // if( isset($_POST['driver']) ){
  //   $driver = $_POST['driver']; 
  // } else {
  //   $requestBody = file_get_contents('php://input');
  //   $parsed = json_decode($requestBody, true);
  //   $driver = $parsed['driver'];
  // }

  $stopType = '';
  // if( isset($_POST['stopType']) ){
  //   $stopType = $_POST['stopType']; 
  // } else {
  //   $requestBody = file_get_contents('php://input');
  //   $parsed = json_decode($requestBody, true);
  //   $stopType = $parsed['stopType'];
  // }

  $stop = '';
  // if( isset($_POST['stop']) ){
  //   $stop = $_POST['stop']; 
  // } else {
  //   $requestBody = file_get_contents('php://input');
  //   $parsed = json_decode($requestBody, true);
  //   $stop = $parsed['stop'];
  // }

  $stopId = '';
  // if( isset($_POST['stopId']) ){
  //   $stopId = $_POST['stopId']; 
  // } else {
  //   $requestBody = file_get_contents('php://input');
  //   $parsed = json_decode($requestBody, true);
  //   $stopId = $parsed['stopId'];
  // }

  $exit = [];

  // Send Notification
    $sql = "SELECT deviceId FROM deviceIds WHERE driverId = 11";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $content = array( 
          "en" => $stopType.' - '.$stop
        );
        $headings = array(
          "en" => 'Stop Added'
        );

        $fields = array(
          'app_id' => "9f03606d-9cc7-46a9-8083-f25629aba6be",
          'include_player_ids' => array($row['deviceId']),
          'data' => array("stop" => $stopId),
          'large_icon' =>"ic_launcher_round.png",
          'contents' => $content,
          'headings' => $headings
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic YzNkMzdlYTktZDI4Yy00YmY0LWJhM2EtMDAyNWIzMGEwMTFl'));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_POST, TRUE);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

          $response = curl_exec($ch);
          curl_close($ch);

          // $response = sendMessage();
          $return["allresponses"] = $response;
          $return = json_encode( $return);
      }
    } else {
      $exit = '0 results';
    }

    return $exit;


?>