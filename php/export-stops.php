<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");

  require_once('connect.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;

  $response = [];
  $stops = [["ID", "Driver ID", "Dispatcher ID", "Address", "Type", "Size", "Status", "Container Number", "Container Number 2", "Borough", "Comments", "Signature", "Date Created", "Time Created", "Date Fulfilled", "Time Fulfilled"] ];

  $query = 'SELECT * FROM stops';
  $result = $conn->query($query);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      array_push( $stops, [ $row["ID"], $row["driverId"], $row["dispatcherId"], $row["address"], $row["type"], $row["size"], $row["status"], $row["containerNum"], $row["containerNum2"], $row["borough"], $row["comments"], $row["signature"], $row["dateCreated"], $row["timeCreated"], $row["dateFulfilled"], $row["timeFulfilled"] ] );
    }
  }

  $excel = new SimpleExcel('csv');
  $excel->writer->setData(
      $stops
  );
  $excel->writer->saveFile('example');

  print_r($response);
?>