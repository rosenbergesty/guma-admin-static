<?php
  // Fetch drivers
  header("Access-Control-Allow-Origin: *");

  // Connect to db
  require_once('connect.php');

  // Insert dispatcher
  $sql = "SELECT count(ID) AS num_rows FROM drivers";
  $result = $conn->query($sql);
  $row = $result->fetch_object();

  echo $row->num_rows;

  $conn->close();
?>