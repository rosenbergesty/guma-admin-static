<?php
  header("Access-Control-Allow-Origin: *");
  for ($i = 1; $i <= 10; $i++) {
    echo json_encode($i);
  }
?>