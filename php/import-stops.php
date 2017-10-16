<?php
  // Import Drivers from CSV
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Cache-Control, X-Requested-With");

  require_once('connect.php');
  require_once('lib/SimpleExcel/SimpleExcel.php');
  use SimpleExcel\SimpleExcel;
  use SimpleExcel\Spreadsheet\Worksheet;

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

      $addressCol = array_search('address', array_map('strtolower', $arr[0])) ?: 0;
      $driverIdCol = array_search('driver id', array_map('strtolower', $arr[0])) ?: 1;
      $dispatcherIdCol = array_search('dispatcher id', array_map('strtolower', $arr[0])) ?: 2;
      $typeCol = array_search('type', array_map('strtolower', $arr[0])) ?: 3;
      $sizeCol = array_search('size', array_map('strtolower', $arr[0])) ?: 4;
      $statusCol = array_search('status', array_map('strtolower', $arr[0])) ?: 5;

      $containerNumCol = array_search('container number', array_map('strtolower', $arr[0])) ?: 6;
      $containerNum2Col = array_search('container number 2', array_map('strtolower', $arr[0])) ?: 7;
      $boroughCol = array_search('borough', array_map('strtolower', $arr[0])) ?: 8;
      $commentsCol = array_search('comments', array_map('strtolower', $arr[0])) ?: 9;
      $signatureCol = array_search('signature', array_map('strtolower', $arr[0])) ?: 10;

      $dateCol = array_search('date created', array_map('strtolower', $arr[0])) ?: 11;
      $timeCol = array_search('time created', array_map('strtolower', $arr[0])) ?: 12;
      $dateFillCol = array_search('date fulfilled', array_map('strtolower', $arr[0])) ?: 13;
      $timeFillCol = array_search('time fulfilled', array_map('strtolower', $arr[0])) ?: 14;

      $existing = [];
      $failed = [];
      $failedEmails = [];

      $response = [];

      foreach($arr as $key => $val){
        if(!$key == 0){
          if(strlen($val[$addressCol]) > 0){
              $write_query = 'INSERT INTO stops (address, driverId, dispatcherId, type, size, status, containerNum, containerNum2, borough, comments, signature, dateCreated, timeCreated, dateFulfilled, timeFulfilled) VALUES ("'.$val[$addressCol].'", "'.$val[$driverIdCol].'", "'.$val[$dispatcherIdCol].'", "'.$val[$typeCol].'", "'.$val[$sizeCol].'", "'.$val[$statusCol].'", "'.$val[$containerNumCol].'", "'.$val[$containerNum2Col].'", "'.$val[$boroughCol].'", "'.$val[$commentsCol].'", "'.$val[$signatureCol].'", "'.$val[$dateCol].'", "'.$val[$timeCol].'", "'.$val[$dateFillCol].'", "'.$val[$timeFillCol].'")';
              if($conn->query($write_query)){

              } else {
                array_push($failed, [$val[$addressCol], $val[$typeCol], $conn->error]);
              } 

          }
        }
      }

      $resp = [];
      if(count($failed) > 0){
        $resp['failed'] = [500, $failed];
      }
      $response = ['code'=>200, 'data'=>$resp];
    }

  } catch (RuntimeException $e){
    $response = ['code'=>500, 'data'=>$e->getMessage()];
  }

  echo json_encode($response);
?>