<?php
  // Connect to database for Guma Apps

  // $server = "localhost";
  // $username = "estyrose_guma";
  // $password = "Pass@guma12";
  // $dbname = "estyrose_guma";

  $server = "rds-mysql-demo.cino6baptdtn.us-east-1.rds.amazonaws.com";
  $username = "demo";
  $password = "password";
  $dbname = "demo";

  $conn = new mysqli($server, $username, $password, $dbname);

  if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
  }

?>