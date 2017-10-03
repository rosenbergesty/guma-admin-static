<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");

  require_once('connect.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;

  $response = [];
  $drivers = [["ID", "Name", "Email"]];

  $query = 'SELECT * FROM drivers';
  $result = $conn->query($query);
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      array_push($drivers, [ $row["ID"], $row["name"], $row["email"] ]);
    }
  }

  $excel = new SimpleExcel('csv');
  $excel->writer->setData(
      $drivers
  );
  $excel->writer->saveFile('example');

  print_r($response);
?>