<?php
  // Send Notification
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  // require_once('connect.php');
  include('send-notification.php');

  $resp = sendNotification(11, 'Dropoff', '123 First Street, Brooklyn, NY', 1);
  echo $resp;

?>