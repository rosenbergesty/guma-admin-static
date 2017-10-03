<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Cache-Control, X-Requested-With");

  require_once('connect.php');
  require_once('lib/PHPMailer/src/PHPMailer.php');
  require_once('lib/PHPMailer/src/SMTP.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  try{
    if(!isset($_FILES['upfile']['error']) || is_array($_FILES['upfile']['error'])) {
      throw new RuntimeException('Invalid parameters.');
    }

    switch($_FILES['upfile']['error']){
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new RuntimeException('Exceeded filesize limit.');
      default:
        throw new RuntimeException('Unknown errors.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimes = array('text/csv');
    if(!in_array($_FILES['upfile']['type'],$mimes)){
      throw new RuntimeException('Invalid file format');
    }

    $target = 'uploads/';
    $target_file = $target.basename($_FILES['upfile']['name']);
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_file)){

      $excel = new SimpleExcel('CSV');
      $excel->parser->loadFile($target.$_FILES['upfile']['name']);
      $arr = $excel->parser->getField();

      $nameCol = array_search('name', array_map('strtolower', $arr[0]));
      $emailCol = array_search('email', array_map('strtolower', $arr[0]));

      $existing = [];
      $failed = [];
      $failedEmails = [];

      $response = [];

      foreach($arr as $key => $val){
        if(!$key == 0){
          if((strlen($val[$nameCol]) > 0) && (strlen($val[$emailCol]) > 0)){

            $query = 'SELECT COUNT(*) as total FROM drivers WHERE email="'.$val[$emailCol].'"';
            $result = $conn->query($query);
            $data = $result->fetch_assoc();

            if($data['total'] < 1){
              $write_query = 'INSERT INTO drivers (name, email) VALUES ("'.$val[$nameCol].'", "'.$val[$emailCol].'")';
              if($conn->query($write_query)){

                // Send Email
                $mail = new PHPMailer(true);
                try {
                  // Server settings
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
                  $mail->addAddress($val[$emailCol], $val[$nameCol]);

                  //Content
                  $mail->isHTML(true);
                  $mail->Subject = 'Welcom to Guma App';
                  $mail->Body    = '<div style="background: #007BFF; padding: 10px; width: 100%;"><h1 style="color: #FFFFFF; font-family: Helvetica, sans-serif; font-weight: normal;">Guma</h1></div><div style="padding: 100px; background: #ECECEC;"><p style="color: #555555; margin-bottom: 30px;">Welcome to Guma App. Activate your account by creating a password.</p><a href="#" style="display: block; background: #FF2D55; color: #FFFFFF; padding: 10px; width: 130px; text-decoration: none; text-transform: uppercase;">Create Password</a></div><div style="background: #555555; padding: 10px;"><p style="color: #CCCCCC; font-size: 10px;">This email was sent through the Guma App Backend. If you believe it was sent in error, contact the admin at '.''.'. We are truly sorry for the mix up.';
                  $mail->AltBody = 'Welcome to Guma App. Activate your account by creating a password here: ...';

                  $mail->send();

                } catch (Exception $e) {
                  array_push($failedEmails, [$val[$namecol], $val[$emailCol], $mail->ErrorInfo]);
                }
              } else {
                array_push($failed, [$val[$nameCol], $val[$emailCol], $conn->error]);
              } 

            } else {
              array_push($existing, [$val[$nameCol], $val[$emailCol]]);
            }

          }
        }
      }

      $resp = [];
      if(count($failed) > 0){
        $resp['failed'] = [500, $failed];
      }
      if(count($failedEmails) > 0){
        $resp['failedEmails']= [500, $failedEmails];
      }
      if(count($existing) > 0){
        $resp['existing'] = [500, $existing];
      }
      $response = ['code'=>200, 'data'=>$resp];
    }

  } catch (RuntimeException $e){
    $response = ['code'=>500, 'data'=>$e->getMessage()];
  }

  echo json_encode($response);
?>