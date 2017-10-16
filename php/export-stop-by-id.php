<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");

  require_once('connect.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;

  if($_POST){
    $id = $_POST['id'];

    $response = [];
    $stops = [["ID", "Address", "Type", "Size", "Status", "Date Created", "Time Created", "Container Number", "Container Number 2", "Borough", "Comments", "Date Fulfilled", "Time Fulfilled"]];

    $sql = "SELECT * FROM stops WHERE ID = '$id'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        array_push($stops, [$row["ID"], $row["address"], $row["type"], $row["size"], $row["status"], $row["dateCreated"], $row["timeCreated"], $row["containerNum"], $row["containerNum2"], $row["borough"], $row["comments"], $row["dateFulfilled"], $row["timeFulfilled"] ]);
      }
    }

    $excel = new SimpleExcel('csv');
    $excel->writer->setData(
        $stops
    );
    $excel->writer->saveFile('guma_stop.csv');
  }

  echo json_encode($response);
?>