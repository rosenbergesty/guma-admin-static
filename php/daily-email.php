<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once('connect.php');

  require 'lib/PHPMailer/src/Exception.php';
  require 'lib/PHPMailer/src/PHPMailer.php';
  require 'lib/PHPMailer/src/SMTP.php';

  $response = [];
  $table = '';
  $stops = [["ID", "Driver ID", "Dispatcher ID", "Address", "Type", "Size", "Status", "Container Number", "Container Number 2", "Borough", "Comments", "Signature", "Date Created", "Time Created", "Date Fulfilled", "Time Fulfilled"] ];

  $query = 'SELECT * FROM stops';
  $result = $conn->query($query);
  if($result->num_rows > 0){
    $table = '<table>';
    while($row = $result->fetch_assoc()){
      array_push( $stops, [ $row["ID"], $row["driverId"], $row["dispatcherId"], $row["address"], $row["type"], $row["size"], $row["status"], $row["containerNum"], $row["containerNum2"], $row["borough"], $row["comments"], $row["signature"], $row["dateCreated"], $row["timeCreated"], $row["dateFulfilled"], $row["timeFulfilled"] ] );
      $table .= '<tr><td>'.$row["address"].'</td><td>'.$row["driverId"].'</td><td>'. $row["dispatcherId"].'</td><td>'.$row["type"].'</td><tr>';
    }
    $table .= '</table>';
  }

  // Send email to user
  $mail = new PHPMailer(true);
  $email = 'rosenbergesty@gmail.com';
  try {
      //Server settings
      // $mail->SMTPDebug = 2;
      $mail->isSMTP();
      $mail->Host = 'box1040.bluehost.com;mail.estyrosenberg.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'info@estyrosenberg.com';
      $mail->Password = 'E797-3474r';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;

      //Recipients
      $mail->setFrom('info@estyrosenberg.com', 'Guma App');
      $mail->addAddress($email);
      // $mail->addBCC(''); -- Send to Guma Admin

      //Content
      $mail->isHTML(true);
      $mail->Subject = 'Welcom to Guma App';
      $mail->Body    = '<div style="background: #007BFF; padding: 10px; width: 100%;"><h1 style="color: #FFFFFF; font-family: Helvetica, sans-serif; font-weight: normal;">Guma</h1></div><div style="padding: 100px; background: #ECECEC;">'.$table.'<p style="color: #CCCCCC; font-size: 10px;">This email was sent through the Guma App Backend. If you believe it was sent in error, contact the admin at '.''.'. We are truly sorry for the mix up.</p></div>';
      $mail->AltBody = '...';

      $mail->send();

  } catch (Exception $e) {

  }

  print_r($response);
?>