<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  // Add driver
  header("Access-Control-Allow-Origin: *");
  
  // Connect to db
  require_once('connect.php');

  require 'lib/PHPMailer/src/Exception.php';
  require 'lib/PHPMailer/src/PHPMailer.php';
  require 'lib/PHPMailer/src/SMTP.php';

  // Get data
  $name = $_POST['name'];
  $email = strtolower($_POST['email']);

  $response = [];

  // Insert dispatcher
  $sql = "INSERT INTO drivers (name, email) VALUES ('$name', '$email')";
  if($conn->query($sql) === TRUE){
    array_push($response, ['code'=>'200', 'response'=>'Successfully added']);
  }  else {
    array_push($response, ['code'=>'500', 'response'=>"Error: " . $sql . "<br>" . $conn->error]);
  }

  // Send email to user
  $mail = new PHPMailer(true);
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
      $mail->addAddress($email, $name);
      // $mail->addBCC(''); -- Send to Guma Admin

      //Content
      $mail->isHTML(true);
      $mail->Subject = 'Welcom to Guma App';
      $mail->Body    = '<div style="background: #007BFF; padding: 10px; width: 100%;"><h1 style="color: #FFFFFF; font-family: Helvetica, sans-serif; font-weight: normal;">Guma</h1></div><div style="padding: 100px; background: #ECECEC;"><p style="color: #555555; margin-bottom: 30px;">Welcome to Guma App. Activate your account by creating a password.</p><a href="http://estyrosenberg.com/guma/admin/password.html?id='.$email.'" style="display: block; background: #FF2D55; color: #FFFFFF; padding: 10px; width: 130px; text-decoration: none; text-transform: uppercase;">Create Password</a></div><div style="background: #555555; padding: 10px;"><p style="color: #CCCCCC; font-size: 10px;">This email was sent through the Guma App Backend. If you believe it was sent in error, contact the admin at '.''.'. We are truly sorry for the mix up.';
      $mail->AltBody = 'Welcome to Guma App. Activate your account by creating a password here: ...';

      $mail->send();
      // echo 'Message has been sent';
  } catch (Exception $e) {
      // echo 'Message could not be sent.';
      // echo 'Mailer Error: ' . $mail->ErrorInfo;
  }

  echo json_encode($response);

  $conn->close();
?>