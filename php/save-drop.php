<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  // Fetch stops by driverId
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  // Connect to db
  require_once('connect.php');

  // FPDF
  require('lib/fpdf/fpdf.php');

  // PDF Header & Footer
  class PDF extends FPDF {
    // Page header
    function Header() {
      $this->SetFont('Times','',8);

      // Header
      $this->setX(180);
      $this->Cell(10,10,'123456',0,2,'R');

      $this->setX(10);
      $this->Cell(10,4,'Guma Corp.', 0, 0);
      $this->setX(180);
      $this->Cell(10,4,'Date: 01/01/2001', 0, 2, 'R');
      $this->setX(10);
      $this->Cell(10,4,'Roll-off Containers', 0, 0);
      $this->setX(180);
      $this->Cell(10,4,'3:12', 0, 2, 'R');
      $this->setX(10);
      $this->Cell(10,4,'302 Plymouth St.', 0, 2);
      $this->Cell(10,4,'Brooklyn, NY 11201', 0, 2);
      $this->Cell(10,4,'718-858-9805 Fax 718-522-0073', 0, 2);

      // Line break
      $this->Ln(20);
    }

    // Page footer
    function Footer() {
      $this->SetY(-15);
      $this->SetFont('Arial','I',8);
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
  }

  // Mailer
  require 'lib/PHPMailer/src/Exception.php';
  require 'lib/PHPMailer/src/PHPMailer.php';
  require 'lib/PHPMailer/src/SMTP.php';

  // Get data
  $id = '';
  if( isset($_POST['id']) ){
    $id = $_POST['id']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $id = $parsed['id'];
  }
  
  $time = '';
  if( isset($_POST['time']) ){
    $time = $_POST['time']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $time = $parsed['time'];
  }

  $date = '';
  if( isset($_POST['date']) ){
    $date = $_POST['date']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $date = $parsed['date'];
  }

  $container = '';
  if( isset($_POST['container']) ){
    $container = $_POST['container']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $container = $parsed['container'];
  }

  $comments = '';
  if( isset($_POST['comments']) ){
    $comments = $_POST['comments']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $comments = $parsed['comments'];
  }

  $signature = '';
  if( isset($_POST['signature']) ){
    $signature = $_POST['signature']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $signature = $parsed['signature'];
  }

  $driver = '';
  if( isset($_POST['driver']) ){
    $driver = $_POST['driver']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $driver = $parsed['driver'];
  }

  $drop = '';
  if( isset($_POST['dropTicket']) ){
    $drop = $_POST['dropTicket']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $drop = $parsed['dropTicket'];
  }

  $address = '';
  if( isset($_POST['address']) ){
    $address = $_POST['address']; 
  } else {
    $requestBody = file_get_contents('php://input');
    $parsed = json_decode($requestBody, true);
    $address = $parsed['address'];
  }

  $response = [];

  // Save Drop
  if(strlen($address) > 0){
    $sql = "UPDATE stops SET timeFulfilled='$time', dateFulfilled='$date', containerNum='$container', comments='$comments', signature='$signature', status='completed', dropTicket='$drop' WHERE ID='$id'";
    if ($conn->query($sql) === TRUE) {

      // Drop Ticket
      $pdf = new PDF();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Times','',12);
      $pdf->setY(50);
      $pdf->setX(10);
      $pdf->Cell(13,10,'Driver: ',0,0 );
      $pdf->SetFont('Times','U',12);
      $pdf->Cell(20,10,$driver,0,2);
      $pdf->SetFont('Times','',12);
      $pdf->setY(57);
      $pdf->setX(10);
      $pdf->Cell(9,10,'Site:',0,0 );
      $pdf->SetFont('Times','U',12);
      $pdf->Cell(20,10,$address,0,2);
      $pdf->SetFont('Times','',12);
      $pdf->setY(64);
      $pdf->setX(10);
      $pdf->Cell(33,10,'Container Number:',0,0 );
      $pdf->SetFont('Times','U',12);
      $pdf->Cell(20,10,$container,0,2);

      $pdf->setY(78);
      $pdf->setX(10);
      $pdf->SetFont('Times','',12);
      $pdf->Cell(20,10,'Signature: ',0,2);
      $pdf->Image('https://firebasestorage.googleapis.com/v0/b/guma-construction-apps.appspot.com/o/signatures%2Fsignature-52.png?alt=media&token=10665d4a-1016-4f7a-839f-5c93a64c2b3b',10,80,90,0,'PNG');

      $pdf->Output("drop.pdf", "F");
      $response = ['code' => '200', 'message' => base64_encode(file_get_contents("drop.pdf"))];


      // Send Email
      $pdfDoc = $pdf->Output('', 'S');
      $mail = new PHPMailer(true);
      try {
          //Server settings
          $mail->isSMTP();
          $mail->Host = 'box1040.bluehost.com;mail.estyrosenberg.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'info@estyrosenberg.com';
          $mail->Password = 'E797-3474r';
          $mail->SMTPSecure = 'ssl';
          $mail->Port = 465;

          //Recipients
          $mail->setFrom('info@estyrosenberg.com', 'Guma App');
          $mail->addAddress('rosenbergesty@gmail.com', 'Esty Rosenberg');

          //Content
          $mail->isHTML(true);
          $mail->Subject = 'Drop Ticket #'.$id;
          $mail->Body    = '<div style="background: #007BFF; padding: 10px; width: 100%;"><h1 style="color: #FFFFFF; font-family: Helvetica, sans-serif; font-weight: normal;">Guma</h1></div><div style="padding: 20px 10px; background: #ECECEC;"><p>A drop ticket was generated. See attachment.</p></div>';
          $mail->AltBody = 'Welcome to Guma App. Activate your account by creating a password here: ...';
          $mail->addStringAttachment($pdfDoc, 'drop-ticket-'.$id.'.pdf');

          $mail->send();
      } catch (Exception $e) {

      }


    } else {
      $response = ['code' => '500', 'message' => 'Error updating: '.$conn->error];
    }
  }

  echo json_encode($response);

  $conn->close();
?>